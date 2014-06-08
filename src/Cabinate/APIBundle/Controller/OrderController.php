<?php

namespace Cabinate\APIBundle\Controller;

class OrderController extends APIBaseController 
{
    public function coptionsAction()
    {} // "options_restaurants" [OPTIONS] /restaurants

    public function cgetAction()
    {} // "get_restaurants"     [GET] /restaurants

    public function cnewAction()
    {} // "new_restaurants"     [GET] /restaurants/new

    public function cpostAction()
    {} // "post_restaurants"    [POST] /restaurants

    public function cpatchAction()
    {} // "patch_restaurants"   [PATCH] /restaurants

    public function getAction($slug)
    {} // "get_restaurant"      [GET] /restaurants/{slug}

    public function editAction($slug)
    {} // "edit_restaurant"     [GET] /restaurants/{slug}/edit

    public function putAction($slug)
    {} // "put_restaurant"      [PUT] /restaurants/{slug}

    public function patchAction($slug)
    {} // "patch_restaurant"    [PATCH] /restaurants/{slug}

    public function lockAction($slug)
    {} // "lock_restaurant"     [PATCH] /restaurants/{slug}/lock

    public function banAction($slug)
    {} // "ban_restaurant"      [PATCH] /restaurants/{slug}/ban

    public function removeAction($slug)
    {} // "remove_restaurant"   [GET] /restaurants/{slug}/remove

    public function deleteAction($slug)
    {} // "delete_restaurant"   [DELETE] /restaurants/{slug}


}
