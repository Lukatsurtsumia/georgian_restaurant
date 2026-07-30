<?php

namespace Database\Seeders;

use App\Models\MenuCategory;
use App\Models\MenuItem;
use Illuminate\Database\Seeder;

class MenuSeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'slug' => 'khachapuri',
                'name_en' => 'Khachapuri',
                'name_fr' => 'Khachapouri',
                'items' => [
                    [
                        'name_en' => 'Adjarian Khachapuri',
                        'name_fr' => 'Khachapouri Adjarien',
                        'description_en' => 'Boat-shaped bread filled with Imeretian cheese, topped with a soft egg and a knob of butter.',
                        'description_fr' => 'Pain en forme de barque garni de fromage imérétien, œuf coulant et noisette de beurre.',
                        'price' => 14.50,
                        'is_vegetarian' => true,
                        'is_featured' => true,
                    ],
                    [
                        'name_en' => 'Imeretian Khachapuri',
                        'name_fr' => 'Khachapouri Imérétien',
                        'description_en' => 'Round cheese bread, the everyday classic from western Georgia.',
                        'description_fr' => 'Pain au fromage rond, le classique du quotidien venu de l\'ouest de la Géorgie.',
                        'price' => 11.00,
                        'is_vegetarian' => true,
                    ],
                    [
                        'name_en' => 'Megruli Khachapuri',
                        'name_fr' => 'Khachapouri Mégrélien',
                        'description_en' => 'Cheese bread topped with an extra layer of melted cheese.',
                        'description_fr' => 'Pain au fromage recouvert d\'une couche supplémentaire de fromage fondu.',
                        'price' => 13.00,
                        'is_vegetarian' => true,
                    ],
                ],
            ],
            [
                'slug' => 'khinkali',
                'name_en' => 'Khinkali',
                'name_fr' => 'Khinkalis',
                'items' => [
                    [
                        'name_en' => 'Beef & Pork Khinkali',
                        'name_fr' => 'Khinkalis Bœuf et Porc',
                        'description_en' => 'Hand-pulled dumplings filled with spiced beef and pork broth, served by the piece.',
                        'description_fr' => 'Raviolis façonnés à la main, farcis de bœuf et porc épicés dans leur bouillon, à la pièce.',
                        'price' => 2.20,
                        'is_featured' => true,
                    ],
                    [
                        'name_en' => 'Mushroom & Cheese Khinkali',
                        'name_fr' => 'Khinkalis Champignons et Fromage',
                        'description_en' => 'Vegetarian dumplings with mushroom, sulguni cheese and herbs.',
                        'description_fr' => 'Raviolis végétariens aux champignons, fromage sulguni et herbes.',
                        'price' => 2.20,
                        'is_vegetarian' => true,
                    ],
                    [
                        'name_en' => 'Spicy Lamb Khinkali',
                        'name_fr' => 'Khinkalis à l\'Agneau Épicé',
                        'description_en' => 'Dumplings filled with lamb, cumin and hot pepper.',
                        'description_fr' => 'Raviolis farcis à l\'agneau, cumin et piment.',
                        'price' => 2.40,
                        'is_spicy' => true,
                    ],
                ],
            ],
            [
                'slug' => 'starters',
                'name_en' => 'Cold Starters',
                'name_fr' => 'Entrées Froides',
                'items' => [
                    [
                        'name_en' => 'Pkhali Trio',
                        'name_fr' => 'Trio de Pkhali',
                        'description_en' => 'Walnut pâté of spinach, beetroot and green bean, topped with pomegranate seeds.',
                        'description_fr' => 'Pâté aux noix d\'épinard, betterave et haricot vert, éclats de grenade.',
                        'price' => 9.50,
                        'is_vegetarian' => true,
                        'is_featured' => true,
                    ],
                    [
                        'name_en' => 'Badrijani Nigvzit',
                        'name_fr' => 'Aubergines aux Noix',
                        'description_en' => 'Rolled eggplant slices filled with walnut and garlic paste.',
                        'description_fr' => 'Roulés d\'aubergine farcis d\'une pâte de noix et d\'ail.',
                        'price' => 9.00,
                        'is_vegetarian' => true,
                    ],
                    [
                        'name_en' => 'Lobio',
                        'name_fr' => 'Lobio',
                        'description_en' => 'Slow-cooked red beans with coriander, walnut and spices, served with mchadi cornbread.',
                        'description_fr' => 'Haricots rouges mijotés à la coriandre, aux noix et aux épices, servis avec pain de maïs mchadi.',
                        'price' => 9.50,
                        'is_vegetarian' => true,
                    ],
                ],
            ],
            [
                'slug' => 'grill',
                'name_en' => 'From the Grill',
                'name_fr' => 'À la Braise',
                'items' => [
                    [
                        'name_en' => 'Mtsvadi (Pork Skewers)',
                        'name_fr' => 'Mtsvadi (Brochettes de Porc)',
                        'description_en' => 'Marinated pork skewers grilled over vine wood, served with tkemali plum sauce.',
                        'description_fr' => 'Brochettes de porc marinées grillées au sarment de vigne, sauce tkemali aux prunes.',
                        'price' => 19.50,
                        'is_featured' => true,
                    ],
                    [
                        'name_en' => 'Chicken Tabaka',
                        'name_fr' => 'Poulet Tabaka',
                        'description_en' => 'Whole spatchcocked baby chicken, pressed and pan-seared with garlic sauce.',
                        'description_fr' => 'Poussin entier aplati, saisi à la poêle, sauce à l\'ail.',
                        'price' => 18.00,
                    ],
                    [
                        'name_en' => 'Kupati (Spiced Sausage)',
                        'name_fr' => 'Kupati (Saucisse Épicée)',
                        'description_en' => 'Grilled pork sausage with pomegranate and walnut, hot and aromatic.',
                        'description_fr' => 'Saucisse de porc grillée à la grenade et aux noix, épicée et parfumée.',
                        'price' => 16.50,
                        'is_spicy' => true,
                    ],
                ],
            ],
            [
                'slug' => 'desserts',
                'name_en' => 'Desserts',
                'name_fr' => 'Desserts',
                'items' => [
                    [
                        'name_en' => 'Churchkhela',
                        'name_fr' => 'Tchourtchkhéla',
                        'description_en' => 'Walnuts strung and dipped in thickened grape juice, the classic Georgian "candle".',
                        'description_fr' => 'Noix enfilées et trempées dans du jus de raisin épaissi, la "bougie" géorgienne.',
                        'price' => 5.50,
                        'is_vegetarian' => true,
                    ],
                    [
                        'name_en' => 'Gozinaki',
                        'name_fr' => 'Gozinaki',
                        'description_en' => 'Caramelised walnuts and honey, served in bite-size pieces.',
                        'description_fr' => 'Noix caramélisées au miel, en petites bouchées.',
                        'price' => 6.00,
                        'is_vegetarian' => true,
                    ],
                ],
            ],
            [
                'slug' => 'drinks',
                'name_en' => 'Wine & Drinks',
                'name_fr' => 'Vins et Boissons',
                'items' => [
                    [
                        'name_en' => 'Saperavi (glass)',
                        'name_fr' => 'Saperavi (verre)',
                        'description_en' => 'Deep red qvevri wine from Kakheti, dry and full-bodied.',
                        'description_fr' => 'Vin rouge en qvevri de Kakhétie, sec et corsé.',
                        'price' => 7.50,
                        'is_featured' => true,
                    ],
                    [
                        'name_en' => 'Rkatsiteli (glass)',
                        'name_fr' => 'Rkatsiteli (verre)',
                        'description_en' => 'Amber qvevri white wine, dry with notes of quince and honey.',
                        'description_fr' => 'Vin blanc ambré en qvevri, sec, notes de coing et de miel.',
                        'price' => 7.00,
                    ],
                    [
                        'name_en' => 'Borjomi Water',
                        'name_fr' => 'Eau de Borjomi',
                        'description_en' => 'Naturally sparkling mineral water from the Borjomi gorge.',
                        'description_fr' => 'Eau minérale naturellement pétillante des gorges de Borjomi.',
                        'price' => 4.00,
                    ],
                ],
            ],
        ];

        foreach ($categories as $catIndex => $category) {
            $menuCategory = MenuCategory::updateOrCreate(
                ['slug' => $category['slug']],
                [
                    'name_en' => $category['name_en'],
                    'name_fr' => $category['name_fr'],
                    'sort_order' => $catIndex,
                ]
            );

            foreach ($category['items'] as $itemIndex => $item) {
                MenuItem::updateOrCreate(
                    [
                        'menu_category_id' => $menuCategory->id,
                        'name_en' => $item['name_en'],
                    ],
                    [
                        'name_fr' => $item['name_fr'],
                        'description_en' => $item['description_en'],
                        'description_fr' => $item['description_fr'],
                        'price' => $item['price'],
                        'is_vegetarian' => $item['is_vegetarian'] ?? false,
                        'is_spicy' => $item['is_spicy'] ?? false,
                        'is_featured' => $item['is_featured'] ?? false,
                        'sort_order' => $itemIndex,
                    ]
                );
            }
        }
    }
}
