<?php

namespace Multiple\Frontend\Controllers;


class ProductsController extends ControllerBase
{
    public function indexAction()
    {
        return $this->response->redirect('login');
    }
}
