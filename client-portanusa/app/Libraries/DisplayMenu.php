<?php

namespace App\Libraries;

use App\Category;
use App\Subject;
use App\Sale_event;
use App\Header;

class DisplayMenu
{

    public static function categoryNavigation()
    {
        $menu = "";

        $parent_categories = Category::whereRaw('parent IS NULL')->where('publish', 'T')->orderBy('name', 'asc')->get();
        if (!empty($parent_categories)) {
            foreach ($parent_categories as $parent) {

                $child_categories = Category::where('parent', $parent->id)->where('publish', 'T')->orderBy('id', 'desc')->get();
                $class = (count($child_categories) != 0 ? "dropdown-submenu" : "");


                $menu .= "<li class='dropdown-item " . $class . "'>";
                $menu .= "<a href='/product/category/" . $parent->url . "'>" . $parent->name;
                $menu .= (count($child_categories) != 0 ? "<i class='fas fa-chevron-right'></i>" : "");
                $menu .= "</a>";
                if (!empty($child_categories) && count($child_categories) > 0) {
                    $menu .= "<ul class='dropdown-menu'>";

                    foreach ($child_categories as $child) {
                        $grandchild_categories = Category::where('parent', $child->id)->where('publish', 'T')->orderBy('id', 'desc')->get();
                        $class = (count($grandchild_categories) != 0 ? "dropdown-submenu-sub" : "");
                        $menu .= "<li class='dropdown-item " . $class . "'>";
                        $menu .= "<a href='/product/category/" . $child->url . "'>" . $child->name;
                        $menu .= (count($grandchild_categories) != 0 ? "<i class='fas fa-chevron-right'></i>" : "");
                        $menu .= "</a>";
                        if (!empty($grandchild_categories) && count($grandchild_categories) > 0) {
                            $menu .= "<ul class='dropdown-menu'>";

                            foreach ($grandchild_categories as $grandchild) {
                                $menu .= "<li class='dropdown-item'>";
                                $menu .= "<a href='/product/category/" . $grandchild->url . "'>" . $grandchild->name . "</a>";
                                $menu .= "</li>";
                            }

                            $menu .= "</ul>";
                        }
                        $menu .= "</li>";
                    }

                    $menu .= "</ul>";
                }
                $menu .= "</li>";
            }
        }
        return $menu;
    }

    public static function subjectNavigation()
    {
        $menu = "";

        $parent_subjects = Subject::whereRaw('parent IS NULL')->where('publish', 'T')->orderBy('name', 'asc')->get();
        if (!empty($parent_subjects)) {
            foreach ($parent_subjects as $parent) {

                $child_subjects = Subject::where('parent', $parent->id)->where('publish', 'T')->orderBy('id', 'desc')->get();
                $class = (count($child_subjects) != 0 ? "dropdown-submenu" : "");


                $menu .= "<li class='dropdown-item " . $class . "'>";
                $menu .= "<a href='" . (count($child_subjects) != 0 ? "javascript:;" : "/product/subject/" . $parent->url) . "'>" . $parent->name;
                $menu .= (count($child_subjects) != 0 ? "<i class='fas fa-chevron-right'></i>" : "");
                $menu .= "</a>";
                if (!empty($child_subjects) && count($child_subjects) > 0) {
                    $menu .= "<ul classs='dropdown-menu'>";

                    foreach ($child_subjects as $child) {
                        $grandchild_subjects = Subject::where('parent', $child->id)->where('publish', 'T')->orderBy('id', 'desc')->get();
                        $class = (count($grandchild_subjects) != 0 ? "dropdown-submenu-sub" : "");
                        $menu .= "<li class='dropdown-item " . $class . "'>";
                        $menu .= "<a href='/product/subject/" . $child->url . "'>" . $child->name;
                        $menu .= (count($grandchild_subjects) != 0 ? "<i class='fas fa-chevron-right'></i>" : "");
                        $menu .= "</a>";
                        if (!empty($grandchild_subjects) && count($grandchild_subjects) > 0) {
                            $menu .= "<ul class='dropdown-menu'>";

                            foreach ($grandchild_subjects as $grandchild) {
                                $menu .= "<li class='dropdown-item'>";
                                $menu .= "<a href='/product/subject/" . $grandchild->url . "'>" . $grandchild->name . "</a>";
                                $menu .= "</li>";
                            }

                            $menu .= "</ul>";
                        }
                        $menu .= "</li>";
                    }

                    $menu .= "</ul>";
                }
                $menu .= "</li>";
            }
        }
        return $menu;
    }

    public static function eventNavigation()
    {
        $menu = "";
        $today = date('Y-m-d H:i:s');
        $events = Sale_event::where('date_start', '<=', $today)->where('date_end', '>=', $today)->where('enable', 'T')->orderBy('id', 'desc')->get();
        if (!empty($events)) {
            foreach ($events as $event) {
                $menu .= "<li class='nav-item'>";
                $menu .= "<a class='nav-link' href='/product/event/" . $event->url . "'>" . $event->name . "</a>";
                $menu .= "</li>";
            }
        }
        return $menu;
    }

    public static function getLogo()
    {
        $header = Header::find(1);
        $logo = AppConfiguration::assetDomain()->value . "/" . AppConfiguration::logoImagePath()->value . "/" . $header->logo;

        return $logo;
    }

    public static function getIcon()
    {
        $header = Header::find(1);
        $icon = AppConfiguration::assetDomain()->value . "/" . AppConfiguration::logoImagePath()->value . "/" . $header->icon;

        return $icon;
    }

    public static function getCompanyInfo()
    {
        $header = Header::find(1);

        return $header;
    }
}
