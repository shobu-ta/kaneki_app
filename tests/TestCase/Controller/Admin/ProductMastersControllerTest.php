<?php
declare(strict_types=1);

namespace App\Test\TestCase\Controller\Admin;

use Cake\TestSuite\IntegrationTestTrait;
use Cake\TestSuite\TestCase;

class ProductMastersControllerTest extends TestCase
{
    use IntegrationTestTrait;

    // 最低限：ProductMastersに依存するfixtureだけ読む
    protected array $fixtures = [
        'app.ProductMasters',
        // 管理者ログイン必須ならAdminsも（後でfixture作るなら）
        // 'app.Admins',
    ];

    public function testIndex(): void
    {
        // ===== 認証バイパス（ログイン済みを偽装）=====
        $this->session([
            'Auth' => [
                'id' => 1,
                'email' => 'admin@test.com',
                'role' => 'admin',
            ]
        ]);

        // ===== ページへアクセス =====
        $this->get('/admin/product-masters');

        // ===== 200で表示されるか確認 =====
        $this->assertResponseOk();
    }
}
