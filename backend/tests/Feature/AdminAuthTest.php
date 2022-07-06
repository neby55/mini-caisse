<?php

namespace Tests\Feature;

use App\Models\Cart;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class AdminAuthTest extends TestCase
{
    /**
     * @var Product
     */
    private $product;

    /**
     * @var Order
     */
    private $order;

    /**
     * @var Cart
     */
    private $cart;

    public function setUp(): void
    {
        parent::setUp();

        // Generate product, order & cart for tests
        $this->product = Product::factory()->make();
        $this->order = Order::factory()->make();
        $this->cart = Cart::factory()->make();
    }

    /**
     * @return void
     */
    public function test_redirected_to_login_for_not_connected(): void
    {
        $response = $this->get('/admin/products/');
        $response->assertRedirect('/admin/login');
        $response = $this->get('/admin/orders/');
        $response->assertRedirect('/admin/login');
        $response = $this->get('/admin/roles/');
        $response->assertRedirect('/admin/login');
        $response = $this->get('/admin/users/');
        $response->assertRedirect('/admin/login');
    }

    /**
     * @dataProvider permissionsProvider
     */
    public function test_admin_permissions(int $expectedStatus, String $url, String $method, int $roleId, String $withDataFrom='')
    {
        $data = [];
        if (!empty($withDataFrom)) {
            // toArray skip relationship field in serialization, don't know why :(
            $data = $this->$withDataFrom->toArray();
        }

        $response = $this->getResponseOnURLWithRoleId($url, $method, $roleId, $data);
        if (!empty($response)) {
            $response->assertStatus($expectedStatus);
        }
    }

    public function permissionsProvider()
    {
        $tests = [];

        $testsToAdd = [
            // Products
            'product' => [
                'tests' => [
                    'list' => [
                        'url' => '/admin/products',
                        'method' => 'get',
                        'factory' => ''
                    ],
                    'edit' => [
                        'url' => '/admin/product/1',
                        'method' => 'get',
                        'factory' => ''
                    ],
                    'update' => [
                        'url' => '/admin/product/1/createOrUpdate',
                        'method' => 'post',
                        'factory' => 'product'
                    ],
                    'create' => [
                        'url' => '/admin/product/createOrUpdate',
                        'method' => 'post',
                        'factory' => 'product'
                    ]
                ],
                'roles' => [
                    [
                        'role' => 'preparateur',
                        'roleId' => 5,
                        'expectedStatus' => [
                            'list' => 200,
                            'edit' => 403,
                            'update' => 403,
                            'create' => 403
                        ]
                    ],
                    [
                        'role' => 'serveur',
                        'roleId' => 4,
                        'expectedStatus' => [
                            'list' => 200,
                            'edit' => 403,
                            'update' => 403,
                            'create' => 403
                        ]
                    ],
                    [
                        'role' => 'caisse',
                        'roleId' => 3,
                        'expectedStatus' => [
                            'list' => 200,
                            'edit' => 403,
                            'update' => 403,
                            'create' => 403
                        ]
                    ],
                    [
                        'role' => 'gerant',
                        'roleId' => 2,
                        'expectedStatus' => [
                            'list' => 200,
                            'edit' => 200,
                            'update' => 302,
                            'create' => 302
                        ]
                    ],
                    [
                        'role' => 'admin',
                        'roleId' => 1,
                        'expectedStatus' => [
                            'list' => 200,
                            'edit' => 200,
                            'update' => 302,
                            'create' => 302
                        ]
                    ]
                ]
            ],
            // Orders
            'order' => [
                'tests' => [
                    'list' => [
                        'url' => '/admin/orders',
                        'method' => 'get',
                        'factory' => ''
                    ],
                    'edit' => [
                        'url' => '/admin/order/1',
                        'method' => 'get',
                        'factory' => ''
                    ],
                    'update' => [
                        'url' => '/admin/order/1/createOrUpdate',
                        'method' => 'post',
                        'factory' => 'order'
                    ],
                    'create' => [
                        'url' => '/admin/order/createOrUpdate',
                        'method' => 'post',
                        'factory' => 'order'
                    ]
                ],
                'roles' => [
                    [
                        'role' => 'preparateur',
                        'roleId' => 5,
                        'expectedStatus' => [
                            'list' => 200,
                            'edit' => 403,
                            'update' => 403,
                            'create' => 403
                        ]
                    ],
                    [
                        'role' => 'serveur',
                        'roleId' => 4,
                        'expectedStatus' => [
                            'list' => 200,
                            'edit' => 200,
                            'update' => 302,
                            'create' => 302
                        ]
                    ],
                    [
                        'role' => 'caisse',
                        'roleId' => 3,
                        'expectedStatus' => [
                            'list' => 200,
                            'edit' => 200,
                            'update' => 302,
                            'create' => 302
                        ]
                    ],
                    [
                        'role' => 'gerant',
                        'roleId' => 2,
                        'expectedStatus' => [
                            'list' => 200,
                            'edit' => 200,
                            'update' => 302,
                            'create' => 302
                        ]
                    ],
                    [
                        'role' => 'admin',
                        'roleId' => 1,
                        'expectedStatus' => [
                            'list' => 200,
                            'edit' => 200,
                            'update' => 302,
                            'create' => 302
                        ]
                    ]
                ]
            ],
            // Carts
            'cart' => [
                'tests' => [
                    'list' => [
                        'url' => '/admin/order/1/carts',
                        'method' => 'get',
                        'factory' => ''
                    ],
                    'edit' => [
                        'url' => '/admin/order/1/cart/2',
                        'method' => 'get',
                        'factory' => ''
                    ],
                    'update' => [
                        'url' => '/admin/order/1/cart/2/createOrUpdate',
                        'method' => 'post',
                        'factory' => 'order'
                    ],
                    'create' => [
                        'url' => '/admin/order/1/cart/createOrUpdate',
                        'method' => 'post',
                        'factory' => 'order'
                    ]
                ],
                'roles' => [
                    [
                        'role' => 'preparateur',
                        'roleId' => 5,
                        'expectedStatus' => [
                            'list' => 200,
                            'edit' => 403,
                            'update' => 403,
                            'create' => 403
                        ]
                    ],
                    [
                        'role' => 'serveur',
                        'roleId' => 4,
                        'expectedStatus' => [
                            'list' => 200,
                            'edit' => 200,
                            'update' => 302,
                            'create' => 302
                        ]
                    ],
                    [
                        'role' => 'caisse',
                        'roleId' => 3,
                        'expectedStatus' => [
                            'list' => 200,
                            'edit' => 200,
                            'update' => 302,
                            'create' => 302
                        ]
                    ],
                    [
                        'role' => 'gerant',
                        'roleId' => 2,
                        'expectedStatus' => [
                            'list' => 200,
                            'edit' => 200,
                            'update' => 302,
                            'create' => 302
                        ]
                    ],
                    [
                        'role' => 'admin',
                        'roleId' => 1,
                        'expectedStatus' => [
                            'list' => 200,
                            'edit' => 200,
                            'update' => 302,
                            'create' => 302
                        ]
                    ]
                ]
            ]
        ];
        foreach ($testsToAdd as $entity=>$entityTests) {
            foreach ($entityTests['roles'] as $currentTest) {
                foreach ($entityTests['tests'] as $testName=>$currentEntityTest) {
                    $tests[$testName . ' ' . $entity . ' for role ' . $currentTest['role']] = [
                        $currentTest['expectedStatus'][$testName],
                        $currentEntityTest['url'],
                        $currentEntityTest['method'],
                        $currentTest['roleId'],
                        $currentEntityTest['factory']
                    ];
                }
            }
        }
        return $tests;
    }

    /**
     * Test access to an URL with a specific role
     * 
     * @param String $url
     * @param int $roleId
     * @return TestResponse
     */
    private function getResponseOnURLWithRoleId(String $url, String $method, int $roleId, array $data=[]): TestResponse
    {
        $method = strtolower($method);
        if (in_array($method, ['get', 'post', 'put', 'patch', 'delete'])) {
            $user = User::factory()->create();
            DB::table('role_users')->insert([
                'user_id' => $user->id,
                'role_id' => $roleId // 1=admin, 2=gerant, 3=caisse, 4=serveur, 5=preparateur
            ]);
            return $this->actingAs($user)->$method($url, $data);
        }
    }

    // TODO créer des tests pour chaque route par role

    // TODO autre fichier test sur l'API
}
