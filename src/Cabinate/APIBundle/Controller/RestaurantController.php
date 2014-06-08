<?php

namespace Cabinate\APIBundle\Controller;


class RestaurantController extends APIBaseController 
{
    public function coptionsAction()
    {} // "options_restaurants" [OPTIONS] /restaurants

    public function cgetAction()
    {} // "get_restaurants"     [GET] /restaurants

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

    public function deleteAction($slug)
    {} // "delete_restaurant"   [DELETE] /restaurants/{slug}


}
