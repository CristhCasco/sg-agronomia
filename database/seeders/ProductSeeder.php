<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Product::create(
            [
                'name' => 'REMERA',
                'barcode' => '1',
                'brand' => 'NIKE',
                'model' => 'DRY FIT',
                'size' => 'P',
                'color' => 'NEGRO',
                'description' => 'Remera Alta Calidad',
                'cost' => '50000',
                'price' => '75000',
                'stock' => '200',
                'min_stock' => '5',
                'image' => 'https://dummyimage.com/200x150/5c5756/fff',
                'category_id' => '1'
            ] );

            Product::create(
                [
                    'name' => 'CAMISA',
                    'barcode' => '2',
                    'brand' => 'ADIDAS',
                    'model' => 'TORK',
                    'size' => 'M',
                    'color' => 'BLANCO',
                    'description' => 'Camisa de Alta Calidad',
                    'cost' => '75000',
                    'price' => '100000',
                    'stock' => '100',
                    'min_stock' => '2',
                    'image' => 'https://dummyimage.com/200x150/5c5756/fff',
                    'category_id' => '2'
                ] );

                Product::create(
                    [
                        'name' => 'JEAN',
                        'barcode' => '3',
                        'brand' => 'GARCIS',
                        'model' => 'SLIM FIT',
                        'size' => 'G',
                        'color' => 'AZUL',
                        'description' => 'Jean de Alta Calidad',
                        'cost' => '100000',
                        'price' => '120000',
                        'stock' => '500',
                        'min_stock' => '2',
                        'image' => 'https://dummyimage.com/200x150/5c5756/fff',
                        'category_id' => '3'
                    ] );

                    Product::create(
                        [
                            'name' => 'CALZADO',
                            'barcode' => '4',
                            'brand' => 'SPORT',
                            'model' => 'FINO',
                            'size' => '40',
                            'color' => 'NEGRO',
                            'description' => 'Zapato de Alta Calidad',
                            'cost' => '300000',
                            'price' => '350000',
                            'stock' => '300',
                            'min_stock' => '1',
                            'image' => 'https://dummyimage.com/200x150/5c5756/fff',
                            'category_id' => '4'
                        ] );
    }
}
