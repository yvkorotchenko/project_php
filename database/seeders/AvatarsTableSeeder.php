<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Laravue\Models\Avatars;

class AvatarsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $avatarList = [
            'Default' => 'https://stage-static.mt666.at/static/icons/61c0f8e085ba3373d2678b00214d8adc.png',
            'Leone' => 'https://stage-static.mt666.at/static/icons/7bebb49a2babf67d05bbae3e0aef3ad1.jpeg',
            'Haski' => 'https://stage-static.mt666.at/static/icons/eac7c5f71dbd7f2f5414cb555e8c8161.jpeg',
            'DarkLord' => 'https://stage-static.mt666.at/static/icons/7d6f586618fde66cdc7df307d9ccf708.jpeg',
            'BetMen' => 'https://stage-static.mt666.at/static/icons/de02ea0516ba6bb74779b547b4a0af4f.jpeg',
            'Clone' => 'https://stage-static.mt666.at/static/icons/8ba41434977236437041564a083451fb.jpeg',
            'MenAndKet' => 'https://stage-static.mt666.at/static/icons/0a6c837315880a45ca494528e168b01d.jpeg',
            'DogeDe' => 'https://stage-static.mt666.at/static/icons/bc513e32d5d1e29a8add80c1d37fdeb2.jpeg',
            'ToniStark' => 'https://stage-static.mt666.at/static/icons/74e06e2e12fc2fbaace3b3d8133c9e22.jpeg',
            'IroneMen2' => 'https://stage-static.mt666.at/static/icons/e69864cec12343c263404c5a64b6bfab.jpeg',
            'IronMan' => 'https://stage-static.mt666.at/static/icons/f99a3242d959029ae43ad9cacc29dbe5.jpeg',
            'Flesh' => 'https://stage-static.mt666.at/static/icons/50c2bf2916acb1dfb28a169519cb44b3.jpeg',
            'Tree' => 'https://stage-static.mt666.at/static/icons/f7918ed7a3e3ec864a6faa38d03af749.png',
            'Shihua' => 'https://stage-static.mt666.at/static/icons/535b61f9b2f49676a457adf6fc0a66c9.jpeg',
            'Dragone' => 'https://stage-static.mt666.at/static/icons/77b84bde8f3df47188b87393a3c22a87.png',
            'HunterOne' => 'https://stage-static.mt666.at/static/icons/df3461bf12bba37c3b092e0ee67cfcc6.jpeg',
            'HunterTwo' => 'https://stage-static.mt666.at/static/icons/0d4a8109f791022fb22ad676f8afbc1c.jpeg',
            'Abstract' => 'https://stage-static.mt666.at/static/icons/40355d5b7b1b22bc2cd14c2bfc41b625.jpeg',
            'Dzeday' => 'https://stage-static.mt666.at/static/icons/1e378c43c44336bbcfd4d8b8215003a4.jpeg',
            'Witcher' => 'https://stage-static.mt666.at/static/icons/96307bfdb4086001cda0be05eabc5090.jpeg',
            'Gile' => 'https://stage-static.mt666.at/static/icons/5d62ed9c330a4b73cff1a46e7f2caedd.jpeg',
            'WareKraft' => 'https://stage-static.mt666.at/static/icons/6bfffd818c4b91336203575b35ca2d9b.jpeg',
        ];

        foreach ($avatarList as $name => $url) {
            if (!(Avatars::where('name', $name)->exists())) {
                Avatars::create([
                    'name' => $name,
                    'img_url' => "'" .  $url . "'",
                    'visible' => '1',
                    'position' => 1,
                ]);
            }
        }
    }
}
