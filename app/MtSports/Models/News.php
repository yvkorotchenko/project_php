<?php

namespace App\MtSports\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\MtSports\Models\News
 *
 * @property int $id
 * @property string|null $title
 * @property string|null $url
 * @property string|null $url_img
 * @property string|null $min_img
 * @property string|null $big_img
 * @property string|null $announcement_text
 * @property string|null $content
 * @property string $visible
 * @property int $view
 * @property string|null $type
 * @property string|null $type_local
 * @property string|null $date
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|News newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|News newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|News query()
 * @method static \Illuminate\Database\Eloquent\Builder|News whereAnnouncementText($value)
 * @method static \Illuminate\Database\Eloquent\Builder|News whereBigImg($value)
 * @method static \Illuminate\Database\Eloquent\Builder|News whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|News whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|News whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|News whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|News whereMinImg($value)
 * @method static \Illuminate\Database\Eloquent\Builder|News whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|News whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|News whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|News whereUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|News whereUrlImg($value)
 * @method static \Illuminate\Database\Eloquent\Builder|News whereView($value)
 * @method static \Illuminate\Database\Eloquent\Builder|News whereVisible($value)
 * @mixin \Eloquent
 * @property string|null $type_local
 * @method static \Illuminate\Database\Eloquent\Builder|News whereTypeLocal($value)
 */
class News extends Model
{
    use HasFactory;

    protected $connection = 'mtsports';

    public $fillable = [
        'title',
        'url',
        'url_img',
        'min_img',
        'big_img',
        'announcement_text',
        'content',
        'date',
        'type',
        'type_local'
    ];
}
