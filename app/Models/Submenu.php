<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;


class Submenu extends Model{
	
	use Sortable;

    protected $table = 'submenus';

    protected $dates = ['created_at', 'updated_at'];

    public $sortable = ['name', 'route', 'is_nav'];

    public $timestamps = false;




    protected $fillable = [
        
        'is_nav',
        'name',
        'route',

    ];




    protected $attributes = [

        'slug' => '',
        'is_nav' => false,
        'name' => '',
        'route' => '',

        'created_at' => null,
        'updated_at' => null,
        'ip_created' => '',
        'ip_updated' => '',
        'user_created' => '',
        'user_updated' => '',

    ];





    public function menu() {

    	return $this->belongsTo('App\Models\Menu','menu_id','menu_id');

   	}




    // SCOPES

    public function scopePopulate($query){

        return $query->sortable()->orderBy('updated_at', 'desc')->paginate(10);

    }



    public function scopeSearch($query, $key){

        return $query->where(function ($query) use ($key) {
                $query->where('name', 'LIKE', '%'. $key .'%')
                      ->where('route', 'LIKE', '%'. $key .'%');
        });

    }



    public function scopeFindSlug($query, $slug){

        return $query->where('slug', $slug)->firstOrFail();

    }




   	// GETTERS

    public function getLastSubmenuAttribute(){

        $submenu = $this->select('submenu_id')->orderBy('submenu_id', 'desc')->first();

        if($submenu != null){

          return str_replace('SM', '', $submenu->submenu_id);

        }

        return null;

    }



    public function getSubmenuIdIncrementAttribute(){

        $id = 'SM100001';

        if($this->lastSubmenu != null){

            $num =  $this->lastSubmenu + 1;
            
            $id = 'SM' . $num;
        
        }

        return $id;

    }




}
