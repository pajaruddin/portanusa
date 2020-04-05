<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

use Validator;
use Redirect;
use PDF;

use App\User;
use App\Wishlist;
use App\Customer_shipping_address;
use App\Order;
use App\Order_status;
use App\Order_product;
use App\Libraries\AuthUser;
use App\Libraries\Salt;
use App\Libraries\Shipping;
use App\Libraries\AssetRequest;
use App\Libraries\AppConfiguration;
use App\Mail\ForgotPassword;
use App\Mail\Activation;

class UsersController extends Controller
{

    function account()
    {
        if (!Auth::check()) {
            return redirect('/')->with("failed_login", "Please login first");
        }
        $data['user'] = Auth::user();
        $data['asset_domain'] = AppConfiguration::assetDomain()->value;
        $data['customerNpwpImagePath'] = AppConfiguration::customerNpwpImagePath()->value;
        $data['customerKtpImagePath'] = AppConfiguration::customerKtpImagePath()->value;
        $data['title'] = "PortaNusa - My Account";
        return view('user.account')->with($data);
    }

    function accountEdit()
    {
        if (!Auth::check()) {
            return redirect('/')->with("failed_login", "Please login first");
        }

        $data_provinces = array();
        $provinces = Shipping::getProvinces();
        if ($provinces['rajaongkir']['status']['code'] == 200) {
            $data_provinces = $provinces['rajaongkir']['results'];
        }
        $data['data_provinces'] = $data_provinces;

        $data_cities = array();
        $cities = Shipping::getCities(Auth::user()->province_id);
        if ($cities['rajaongkir']['status']['code'] == 200) {
            $data_cities = $cities['rajaongkir']['results'];
        }
        $data['data_cities'] = $data_cities;

        $data['asset_domain'] = AppConfiguration::assetDomain()->value;
        $data['customerNpwpImagePath'] = AppConfiguration::customerNpwpImagePath()->value;
        $data['customerKtpImagePath'] = AppConfiguration::customerKtpImagePath()->value;

        $data['user'] = Auth::user();
        $data['title'] = "PortaNusa - Edit Account";
        return view('user.accountEdit')->with($data);
    }

    function accountPassword()
    {
        if (!Auth::check()) {
            return redirect('/')->with("failed_login", "Please login first");
        }
        $data['user'] = Auth::user();
        $data['title'] = "PortaNusa - Change Password";
        return view('user.accountPassword')->with($data);
    }

    function accountWishlist()
    {
        if (!Auth::check()) {
            return redirect('/')->with("failed_login", "Please login first");
        }
        $user = Auth::user();

        $wishlists = Wishlist::select('products.id', 'products.name', 'code', 'stock_status_id', 'product_stock_status.name as stock_status', 'products.date_start_periode', 'products.date_end_periode', 'products.url as url', 'product_image.image as image', 'able_to_order', 'products.type_status_id')->leftJoin('products', 'wishlists.product_id', '=', 'products.id')->leftJoin('product_image', 'product_image.product_id', '=', 'products.id')->leftJoin('product_stock_status', 'product_stock_status.id', '=', 'products.stock_status_id')->where('product_image.position', 0)->where('products.type_status_id', 1)->where('products.publish', 'T')->where('deleted', 'F')->where('wishlists.customer_id', $user->id)->orderBy('products.created_at', 'desc')->paginate(16);

        $data['asset_domain'] = AppConfiguration::assetDomain()->value;
        $data['image_path'] = AppConfiguration::productImagePath()->value;

        $data['products'] = $wishlists;

        $data['user'] = $user;
        $data['title'] = "PortaNusa - Wishlist";
        return view('user.accountWishlist')->with($data);
    }

    function accountShipping()
    {
        if (!Auth::check()) {
            return redirect('/')->with("failed_login", "Please login first");
        }

        $user = Auth::user();

        $data['shipping_address'] = Customer_shipping_address::where('customer_id', $user->id)->orderBy('id', 'desc')->get();
        $data['user'] = $user;
        $data['title'] = "PortaNusa - Shipping Address";
        return view('user.shipping.page')->with($data);
    }

    function accountCreateShipping()
    {
        if (!Auth::check()) {
            return redirect('/')->with("failed_login", "Please login first");
        }

        $data_provinces = array();
        $provinces = Shipping::getProvinces();
        if ($provinces['rajaongkir']['status']['code'] == 200) {
            $data_provinces = $provinces['rajaongkir']['results'];
        }
        $data['data_provinces'] = $data_provinces;

        $user = Auth::user();

        $data['user'] = $user;
        $data['title'] = "PortaNusa - Create Shipping Address";
        return view('user.shipping.add')->with($data);
    }

    function accountEditShipping($id)
    {
        if (!Auth::check()) {
            return redirect('/')->with("failed_login", "Please login first");
        }
        $user = Auth::user();
        $shipping = Customer_shipping_address::where('customer_id', $user->id)->where('id', $id)->first();
        if (empty($shipping)) {
            abort(404);
        }

        $data_provinces = array();
        $provinces = Shipping::getProvinces();
        if ($provinces['rajaongkir']['status']['code'] == 200) {
            $data_provinces = $provinces['rajaongkir']['results'];
        }
        $data['data_provinces'] = $data_provinces;

        $data_cities = array();
        $cities = Shipping::getCities($shipping->province_id);
        if ($cities['rajaongkir']['status']['code'] == 200) {
            $data_cities = $cities['rajaongkir']['results'];
        }
        $data['data_cities'] = $data_cities;

        $data['user'] = $user;
        $data['shipping'] = $shipping;
        $data['title'] = "PortaNusa - Edit Shipping Address";
        return view('user.shipping.edit')->with($data);
    }

    function accountHistoryOrder()
    {
        if (!Auth::check()) {
            return redirect('/')->with("failed_login", "Please login first");
        }

        $user = Auth::user();

        $orders_status = Order_status::orderBy('id', 'desc')->get();
        $orders = array();
        $order_products = array();
        $total_product = array();
        if (count($orders_status) != 0) {
            foreach ($orders_status as $status) {
                $orders[$status->id] = Order::where('customer_id', $user->id)->where("status", $status->id)->orderBy('id', 'desc')->get();
                if (count($orders[$status->id]) != 0) {
                    foreach ($orders[$status->id] as $order) {
                        $order_products[$order->id] = Order_product::where("order_id", $order->id)->get();
                        $total_product[$order->id] = Order_product::selectRaw('SUM(quantity) as total_qty')
                            ->where('order_id', $order->id)
                            ->groupBy('order_id')
                            ->first();
                    }
                }
            }
        }

        $data['orders_status'] = $orders_status;
        $data['orders'] = $orders;
        $data['order_products'] = $order_products;
        $data['total_product'] = $total_product;

        $data['user'] = $user;
        $data['title'] = "PortaNusa - History Order";
        return view('user.historyOrder')->with($data);
    }

    function accountHistoryOrderDownload($id)
    {
        if (!Auth::check()) {
            return redirect('/')->with("failed_login", "Please login first");
        }

        $user = Auth::user();

        $order = Order::where('customer_id', $user->id)->where('id', $id)->first();
        if (empty($order)) {
            abort(404);
        }
        $order_products = Order_product::where("order_id", $order->id)->get();
        $total_product = Order_product::selectRaw('SUM(quantity) as total_qty')
            ->where('order_id', $order->id)
            ->groupBy('order_id')
            ->first();



        $data['order'] = $order;
        $data['order_products'] = $order_products;
        $data['total_product'] = $total_product;
        $data['user'] = $user;

        // Send data to the view using loadView function of PDF facade
        $pdf = PDF::loadView('user.historyOrderPdf', $data);
        // Finally, you can download the file using download function
        return $pdf->setPaper('a4', 'portrait')->download('order' . date('Ymd', strtotime($order->created_at)) . '.pdf');
        // return view('user.historyOrderPdf')->with($data);

    }

    public function formSignIn(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $ip_address = \Request::ip();
        $now = time();

        $email = $request->input('email');
        $password = $request->input('password');

        $login = AuthUser::login($email, $password);
        if ($login) {
            $token = bin2hex(random_bytes(32));
            Auth::user()->update(['app_token' => $token]);
            return Redirect::back();
        } else {
            return Redirect::back()->with("failed_login", AuthUser::get_message());
        }
    }

    public function logout()
    {
        Auth::user()->update(['app_token' => NULL]);
        Auth::logout();
        return Redirect::back();
    }

    public function formRegister(Request $request)
    {
        $attributeNames = array(
            'first_name' => 'First Name',
            'last_name' => 'Last Name',
            'handphone' => 'Phone Number',
            'person_as' => 'Person As',
            'company_name' => 'Company Name',
            'email' => 'Email',
            'password' => 'Password',
            'confirm_password' => 'Confirm Password',
            'document_file' => 'NPWP',
            'captcha' => 'Captcha',
        );
        $validator = Validator::make($request->all(), array(
            'first_name' => 'required',
            'last_name' => 'required',
            'handphone' => 'required',
            'person_as' => 'required',
            'email' => 'required|email',
            'password' => 'required',
            'captcha' => 'required|captcha'
        ));
        $validator->setAttributeNames($attributeNames);
        if (!$validator->fails()) {
            $user = User::where('email', $request->input('email'))->first();

            $status = "";
            $message = "";

            if (!empty($user)) {
                $status = "failed_register";
                $message = trans('This email is already registered');

                return Redirect::back()->with($status, $message)->withInput();
            } else {
                $first_name = $request->input('first_name');
                $last_name = $request->input('last_name');
                $handphone = $request->input('handphone');
                $person_as = $request->input('person_as');
                $company_name = ($request->input('company_name') == "" ? NULL : $request->input('company_name'));
                $email = $request->input('email');
                $password = $request->input('password');
                $activation_code  = sha1(md5(microtime()));
                $customer_role_id = 1;
                if ($person_as == "Company") {
                    $customer_role_id = 2;
                }
                $salt = Salt::salt();
                $encrypt_password = sha1($password . $salt);
                $active = 0;

                $slug = strtolower(str_slug($first_name, "_"));
                $npwp_file = NULL;
                $asset_request = new AssetRequest;

                if ($request->hasFile('document_file')) {
                    $document_file = $request->file('document_file');
                    $document_filename = $slug . "_" . uniqid();
                    $document_full_filename = $document_filename . "." . $document_file->getClientOriginalExtension();
                    $document_filetype = $document_file->getClientMimeType();
                    $document_filepath = $_FILES['document_file']['tmp_name'];
                    $destination_path = AppConfiguration::customerNpwpImagePath()->value;

                    $upload_document = $asset_request->anonymousUpload($document_filepath, $document_filetype, $document_full_filename, $destination_path, $document_filename);
                    if ($upload_document['code'] != 200) {
                        return Redirect::back()->with('failed_register', $upload_document['description']);
                    } else {
                        $npwp_file = $upload_document['result']['file_name'];
                    }
                }

                $user = new User();
                $user->customer_role_id = $customer_role_id;
                $user->ip_address = \Request::ip();
                $user->email = $email;
                $user->password = $encrypt_password;
                $user->salt = $salt;
                $user->activation_code = $activation_code;
                $user->first_name = $first_name;
                $user->last_name = $last_name;
                $user->handphone = $handphone;
                $user->person_as = $person_as;
                $user->company_name = $company_name;
                $user->document_file = $npwp_file;
                $user->active = $active;

                if ($user->save()) {
                    Mail::to($email)->send(new Activation($user));

                    $status = "success_login";
                    $message = trans('Registration success, please check your email for activation');
                } else {
                    $status = "failed_register";
                    $message = trans('Account unsuccessfully created');
                }
                return Redirect::back()->with($status, $message);
            }
        } else {
            return Redirect::back()->with("failed_register", $validator->errors()->first())->withInput();
        }
    }

    public function activation($activation_code)
    {
        $user = User::where('activation_code', $activation_code)->first();
        if (!empty($user)) {
            $status = "";
            $message = "";

            if ($user->active == 0) {
                // active user
                $user->activation_code = NULL;
                $user->active = 1;
                if ($user->save()) {
                    $status = "success_login";
                    $message = "Congratulations, your account is active";
                } else {
                    $status = "failed_login";
                    $message = "Sorry, your account was not successful activated";
                }
            } else {
                $status = "failed_login";
                $message = "Your account is already active";
            }

            return redirect('/')->with($status, $message);
        } else {
            abort(404);
        }
    }

    public function formAccountEdit(Request $request)
    {
        $attributeNames = array(
            'first_name' => 'First Name',
            'last_name' => 'Last Name',
            'person_as' => 'Person As',
            'company_name' => 'Company Name',
        );
        $validator = Validator::make($request->all(), array(
            'first_name' => 'required',
            'last_name' => 'required',
            'person_as' => 'required',
        ));
        $validator->setAttributeNames($attributeNames);
        if (!$validator->fails()) {

            $status = "";
            $message = "";

            $first_name = $request->input('first_name');
            $last_name = $request->input('last_name');
            $person_as = $request->input('person_as');
            $company_name = ($request->input('company_name') == "" ? NULL : $request->input('company_name'));
            $gender = ($request->input('gender') == "" ? NULL : $request->input('gender'));
            $day = ($request->input('day') == "" ? NULL : $request->input('day'));
            $month = ($request->input('month') == "" ? NULL : $request->input('month'));
            $year = ($request->input('year') == "" ? NULL : $request->input('year'));
            $birthday = NULL;
            if ($day != "" && $month != "" && $year != "") {
                $birthday = $year . "-" . $month . "-" . $day;
            }
            $address = ($request->input('address') == "" ? NULL : $request->input('address'));
            $province_id = ($request->input('province_id') == "" ? NULL : $request->input('province_id'));
            $city_id = ($request->input('city_id') == "" ? NULL : $request->input('city_id'));
            $postal_code = ($request->input('postal_code') == "" ? NULL : $request->input('postal_code'));
            $handphone = ($request->input('handphone') == "" ? NULL : $request->input('handphone'));

            $user = User::find(Auth::user()->id);
            $user->first_name = $first_name;
            $user->last_name = $last_name;
            $user->person_as = $person_as;
            $user->company_name = $company_name;
            $user->gender = $gender;
            $user->birthday = $birthday;
            $user->address = $address;
            $user->province_id = $province_id;
            $user->city_id = $city_id;
            $user->postal_code = $postal_code;
            $user->handphone = $handphone;

            if ($user->save()) {
                $status = "success";
                $message = trans('Your data successfully updated');
            } else {
                $status = "failed";
                $message = trans('Your data unsuccessfully updated');
            }

            return redirect('account/edit')->with($status, $message);
        } else {
            return redirect('account/edit')->withErrors($validator)->withInput();
        }
    }

    function formAccountPassword(Request $request)
    {
        $attributeNames = array(
            'password' => 'Old Password',
            'new_password' => 'New Password',
            'confirm_password' => 'Confirm Password',
        );
        $validator = Validator::make($request->all(), array(
            'password' => 'required',
            'new_password' => 'required',
            'confirm_password' => 'required',
        ));
        $validator->setAttributeNames($attributeNames);
        if (!$validator->fails()) {

            $status = "";
            $message = "";

            $user = Auth::user();
            $password = $request->input('password');
            $new_password = $request->input('new_password');
            $salt = $user->salt;
            $encrypt_password = sha1($password . $salt);
            $encrypt_new_password = sha1($new_password . $salt);

            if ($encrypt_password == $user->password) {
                $user = User::find($user->id);
                $user->password = $encrypt_new_password;

                if ($user->save()) {
                    $status = "success";
                    $message = trans('Your password successfully updated');
                } else {
                    $status = "failed";
                    $message = trans('Your password unsuccessfully updated');
                }
            } else {
                $status = "failed";
                $message = trans('Wrong old password');
            }

            return redirect('account/password')->with($status, $message);
        } else {
            return redirect('account/password')->withErrors($validator)->withInput();
        }
    }

    public function forgotPassword(Request $request)
    {
        if ($request->ajax()) {
            $attributeNames = array(
                'email' => 'Email'
            );

            $validator = Validator::make($request->all(), array(
                'email' => 'required|email'
            ));

            $validator->setAttributeNames($attributeNames);
            if (!$validator->fails()) {

                $email = $request->input('email');

                $user = User::where('email', $email)->where('active', 1)->first();
                if (!empty($user)) {
                    $user->forgotten_password_code = sha1(md5(microtime()));
                    if ($user->save()) {
                        Mail::to($email)->send(new ForgotPassword($user));
                    }

                    $status = "success";
                    $message = "Please check your email to reset your password";
                } else {
                    $status = "error";
                    $message = "The email you entered is not listed in our system";
                }
            } else {
                $status = "error";
                $message = "";
                $errors = $validator->errors();
                foreach ($errors->all() as $error_message) {
                    $message .= $error_message . "<br/>";
                }
            }

            return response()->json(array(
                'status' => $status,
                'message' => $message
            ));
        } else {
            abort(403, 'Unauthorized action.');
        }
    }

    public function resetPassword($forgotten_password_code)
    {
        $user = User::where('forgotten_password_code', $forgotten_password_code)->first();
        if (!empty($user)) {
            if (Auth::check()) {
                return redirect('/');
            }
            $data['forgotten_password_code'] = $forgotten_password_code;
            $data['title'] = "PortaNusa - Reset Password";
            return view('user.resetPassword')->with($data);
        } else {
            abort(404);
        }
    }

    public function formResetPassword(Request $request)
    {
        $attributeNames = array(
            'email' => 'Email',
            'password' => 'Password',
            'confirm_password' => 'Confirm Password',
        );
        $validator = Validator::make($request->all(), array(
            'email' => 'required|email',
            'password' => 'required|min:8',
            'confirm_password' => 'required|same:password',
        ));
        $validator->setAttributeNames($attributeNames);
        $forgotten_password_code = $request->input('forgotten_password_code');
        if (!$validator->fails()) {
            $status = "";
            $message = "";

            $email = $request->input('email');
            $password = $request->input('password');

            $user = User::where("email", $email)->where("forgotten_password_code", $forgotten_password_code)->first();
            if (!empty($user)) {
                $salt = $user->salt;
                $encrypt_password = sha1($password . $salt);

                $user->forgotten_password_code = NULL;
                $user->password = $encrypt_password;
                if ($user->save()) {
                    $status = "success_login";
                    $message = "Your password has been successfully changed, please login with your new password";
                } else {
                    $status = "failed";
                    $message = "Your password was not changed successfully, please try to reset your password again";
                }
            } else {
                $status = "failed";
                $message = "The email you entered is not listed in our system";
            }
            if ($status == "failed") {
                return redirect('/reset-password/' . $forgotten_password_code)->with($status, $message);
            } else {
                return redirect('/')->with($status, $message);
            }
        } else {
            return redirect('/reset-password/' . $forgotten_password_code)->withErrors($validator)->withInput();
        }
    }

    function formAccountCreateShipping(Request $request)
    {
        $attributeNames = array(
            'label' => 'Shipping label',
            'receiver_name' => 'Receiver name',
            'receiver_phone' => 'Receiver phone',
            'address' => 'Address',
            'province_id' => 'Province',
            'city_id' => 'City',
            'postal_code' => 'Postal code',
        );
        $validator = Validator::make($request->all(), array(
            'label' => 'required',
            'receiver_name' => 'required',
            'receiver_phone' => 'required',
            'address' => 'required',
            'province_id' => 'required',
            'city_id' => 'required',
            'postal_code' => 'required',
        ));
        $validator->setAttributeNames($attributeNames);
        if (!$validator->fails()) {

            $status = "";
            $message = "";

            $customer_id = Auth::user()->id;
            $label = $request->input('label');
            $receiver_name = $request->input('receiver_name');
            $receiver_phone = $request->input('receiver_phone');
            $address = $request->input('address');
            $province_id = $request->input('province_id');
            $city_id = $request->input('city_id');
            $postal_code = $request->input('postal_code');

            $model = new Customer_shipping_address();
            $model->customer_id = $customer_id;
            $model->label = $label;
            $model->receiver_name = $receiver_name;
            $model->receiver_phone = $receiver_phone;
            $model->address = $address;
            $model->province_id = $province_id;
            $model->city_id = $city_id;
            $model->postal_code = $postal_code;
            $model->created_at = date('Y-m-d H:i:s');

            if ($model->save()) {
                $status = "success";
                $message = trans('Your data successfully created');
                return redirect('account/shipping')->with($status, $message);
            } else {
                $status = "failed";
                $message = trans('Your data unsuccessfully created');
                return redirect('account/shipping/create')->with($status, $message);
            }
        } else {
            return redirect('account/shipping/create')->withErrors($validator)->withInput();
        }
    }

    function formAccountEditShipping(Request $request)
    {
        $attributeNames = array(
            'label' => 'Shipping label',
            'receiver_name' => 'Receiver name',
            'receiver_phone' => 'Receiver phone',
            'address' => 'Address',
            'province_id' => 'Province',
            'city_id' => 'City',
            'postal_code' => 'Postal code',
        );
        $validator = Validator::make($request->all(), array(
            'label' => 'required',
            'receiver_name' => 'required',
            'receiver_phone' => 'required',
            'address' => 'required',
            'province_id' => 'required',
            'city_id' => 'required',
            'postal_code' => 'required',
        ));
        $validator->setAttributeNames($attributeNames);

        $id = $request->input('id');

        if (!$validator->fails()) {

            $status = "";
            $message = "";

            $customer_id = Auth::user()->id;
            $label = $request->input('label');
            $receiver_name = $request->input('receiver_name');
            $receiver_phone = $request->input('receiver_phone');
            $address = $request->input('address');
            $province_id = $request->input('province_id');
            $city_id = $request->input('city_id');
            $postal_code = $request->input('postal_code');

            $model = Customer_shipping_address::find($id);
            $model->label = $label;
            $model->receiver_name = $receiver_name;
            $model->receiver_phone = $receiver_phone;
            $model->address = $address;
            $model->province_id = $province_id;
            $model->city_id = $city_id;
            $model->postal_code = $postal_code;
            $model->updated_at = date('Y-m-d H:i:s');

            if ($model->save()) {
                $status = "success";
                $message = trans('Your data successfully updated');
                return redirect('account/shipping')->with($status, $message);
            } else {
                $status = "failed";
                $message = trans('Your data unsuccessfully updated');
                return redirect('account/shipping/edit/' . $id)->with($status, $message);
            }
        } else {
            return redirect('account/shipping/edit/' . $id)->withErrors($validator)->withInput();
        }
    }

    public function formAccountDeleteShipping(Request $request)
    {

        if ($request->ajax()) {
            $attributeNames = array(
                'shipping_id' => "Shipping"
            );

            $validator = Validator::make($request->all(), array(
                'shipping_id' => 'required'
            ));

            $validator->setAttributeNames($attributeNames);

            $status = "";
            $message = "";

            if (!$validator->fails()) {

                $user = Auth::user();

                $id = $request->input('shipping_id');
                $customer_id = $user->id;

                $shipping = Customer_shipping_address::where('customer_id', $customer_id)->where('id', $id)->first();
                if ($shipping->delete()) {
                    $status = "success";
                    $message = "Address successfully deleted";
                } else {
                    $status = "error";
                    $message = "Address unsuccessfully deleted";
                }
            } else {
                $status = "error";
                $message = "";
                $errors = $validator->errors();
                foreach ($errors->all() as $error_message) {
                    $message .= $error_message . "<br/>";
                }
            }

            return response()->json(array(
                'status' => $status,
                'message' => $message
            ));
        } else {
            abort(403, 'Unauthorized action.');
        }
    }

    public function updateStatusOrder(Request $request)
    {
        if ($request->ajax()) {
            $user = Auth::user();

            $invoice_no = $request->input('invoice_no');
            $id = $request->input('id');

            $slug = strtolower(str_slug($invoice_no, "_"));
            $transfer_image = NULL;
            $asset_request = new AssetRequest;

            if ($request->hasFile('transfer_image')) {
                $document_file = $request->file('transfer_image');
                $document_filename = $slug;
                $document_full_filename = $document_filename . "." . $document_file->getClientOriginalExtension();
                $document_filetype = $document_file->getClientMimeType();
                $document_filepath = $_FILES['transfer_image']['tmp_name'];
                $destination_path = AppConfiguration::transferImagePath()->value;

                $upload_document = $asset_request->anonymousUpload($document_filepath, $document_filetype, $document_full_filename, $destination_path, $document_filename);
                if ($upload_document['code'] != 200) {
                    $status = "error";
                    $message = $upload_document['description'];
                } else {
                    $transfer_image = $upload_document['result']['file_name'];
                    $order = Order::find($id);
                    $order->transfer_image = $transfer_image;
                    $order->updated_at = date('Y-m-d H:i:s');
                    if ($order->save()) {
                        $status = "success";
                        $message = "Your data successfully sent, our admin will check it";
                    } else {
                        $status = "error";
                        $message = "Your data can't sent, please try again";
                    }
                }
            }

            return response()->json(array(
                'status' => $status,
                'message' => $message
            ));
        } else {
            abort(403, 'Unauthorized action.');
        }
    }

    public function updateOrder(Request $request)
    {
        if ($request->ajax()) {
            $user = Auth::user();

            $id = $request->input('id');
            $order = Order::find($id);
            $order->status = "5";
            $order->updated_at = date('Y-m-d H:i:s');
            if ($order->save()) {
                $status = "success";
                $message = "Your transaction successfully done";
            } else {
                $status = "error";
                $message = "Your data can't sent, please try again";
            }


            return response()->json(array(
                'status' => $status,
                'message' => $message
            ));
        } else {
            abort(403, 'Unauthorized action.');
        }
    }
}
