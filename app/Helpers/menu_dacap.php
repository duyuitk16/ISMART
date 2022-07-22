<?php

function menuTree($menu, $class_parent, $class_item, $parent = 0, $flat = true)
{
    if ($flat) {
        echo "<ul class ='{$class_parent}' >";
        $flat = false;
    } else {
        $lc = false;
        foreach ($menu as $value) {
            if ($value['parent_id'] == $parent) {
                $lc = true;
            }
        }
        if ($lc)
            echo "<ul class ='{$class_item}'>";
        else {
            return;
        }
    }
    foreach ($menu as $value) {
        if ($value['parent_id'] == $parent) {
            $url = 'http://localhost/unitop.vn/back-end/laravel/Admin_Unimart/san-pham/' . $value['slug'];
            echo "<li><a href = '$url'>" . $value['name'] . "</a>";
            menuTree($menu, $class_parent, $class_item, $value['id'], $flat);
        }
        echo "</li>";
    }
    echo "</ul>";
}
function show_data($data)
{
    echo "<pre>";
    print_r($data);
    echo "</pre>";
}
function searchChildren($data, $id, &$child)
{
    foreach ($data as $item) {
        if ($item['parent_id'] == $id) {
            $child[] = $item['id'];
            searchChildren($data, $item['id'], $child);
        }
    }
}
function menuRespon($menu, $class_item, $parent = 0, $flat = false, $level = 0)
{
    if ($flat == true) {
        $lc = false;
        foreach ($menu as $value) {
            if ($value['parent_id'] == $parent) {
                $lc = true;
            }
        }
        if ($lc)
            echo "<ul class ='{$class_item}'>";
        else {
            return;
        }
    }
    foreach ($menu as $value) {
        if ($value['parent_id'] == $parent) {
            $url = 'http://localhost/unitop.vn/back-end/laravel/Admin_Unimart/san-pham/' . $value['slug'];
            echo "<li><a href = '$url'>" . $value['name'] . "</a>";
            $flat = true;
            menuRespon($menu, $class_item, $value['id'], $flat, $level + 1);
            echo "</li>";
        }
    }
    if ($level != 0)
        echo "</ul>";
}
