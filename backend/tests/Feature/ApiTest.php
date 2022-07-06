<?php

namespace Tests\Feature;

use Tests\TestCase;

class ApiTest extends TestCase
{
    /**
     * @dataProvider getUrlGetToCheck
     * @return void
     */
    public function test_get_url_status(int $expectedStatus, String $url) {
        $this->get($url)->assertStatus($expectedStatus);
    }

    public function getUrlGetToCheck() {
        $input = [
            [404, '/api'],
            [200, '/api/orders'],
            [404, '/api/order/erer'],
            [404, '/api/order/1'],
            [200, '/api/orders/1'],
            [405, '/api/orders/1/payment'],
            [405, '/api/orders/1/complete'],
            [404, '/api/orders/12156487894534086'],
            [200, '/api/orders/filters/created'],
            [200, '/api/orders/filters/paid'],
            [404, '/api/orders/filters/completed'],
            [200, '/api/products'],
            [404, '/api/preparations'],
            [200, '/api/preparations/by-orders'],
            [200, '/api/preparations/by-products'],
        ];
        $data = [];
        foreach ($input as $currentInput) {
            $data[$currentInput[0] . ' ' . $currentInput[1]] = $currentInput;
        }
        return $data;
    }
    /**
     * @dataProvider getUrlPostToCheck
     * @return void
     */
    public function test_post_url_status(int $expectedStatus, String $url) {
        $this->post($url)->assertStatus($expectedStatus);
    }

    public function getUrlPostToCheck() {
        $input = [
            [404, '/api'],
            [405, '/api/orders'],
            [404, '/api/order/erer'],
            [404, '/api/order/1'],
            [405, '/api/orders/1'],
            [409, '/api/orders/1/payment'],
            [200, '/api/orders/2/payment'],
            [409, '/api/orders/1/complete'],
            [409, '/api/orders/2/complete'],
            [200, '/api/orders/3/complete'],
            [405, '/api/orders/12156487894534086'],
            [405, '/api/orders/filters/created'],
            [405, '/api/orders/filters/paid'],
            [404, '/api/orders/filters/completed'],
            [405, '/api/products'],
            [404, '/api/preparations'],
            [405, '/api/preparations/by-orders'],
            [405, '/api/preparations/by-products'],
        ];
        $data = [];
        foreach ($input as $currentInput) {
            $data[$currentInput[0] . ' ' . $currentInput[1]] = $currentInput;
        }
        return $data;
    }
}
