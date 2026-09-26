<?php
/**
 * Legal Controller
 */

namespace App\Controllers;

use App\Core\Controller;

class LegalController extends Controller {
    public function about(): void {
        $this->render('portal/legal/about', ['page_title' => 'About Us — EduGov News']);
    }

    public function contact(): void {
        $this->render('portal/legal/contact', ['page_title' => 'Contact Editorial Team — EduGov News']);
    }

    public function privacy(): void {
        $this->render('portal/legal/privacy', ['page_title' => 'Privacy Policy — EduGov News']);
    }

    public function terms(): void {
        $this->render('portal/legal/terms', ['page_title' => 'Terms & Conditions — EduGov News']);
    }

    public function disclaimer(): void {
        $this->render('portal/legal/disclaimer', ['page_title' => 'Official Sources & Legal Disclaimer — EduGov News']);
    }

    public function copyright(): void {
        $this->render('portal/legal/copyright', ['page_title' => 'Copyright & Fair Use Policy — EduGov News']);
    }
}
