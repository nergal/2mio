<?php

/**
 * Модель стран
 *
 * @author nergal
 * @package btlady
 */
class Model_Country extends ORM
{
	protected $_table_name = 'countries';

    protected $_has_many = array(
    	'regions' => array(
    		'model' => 'region',
    		'foreign_key' => 'country_id',
    	),
    );

    public function get_all()
    {
    	$result = (array) $this
    		->order_by('name')
    		->find_all()
    		->as_array();

        /*
    	array_walk($result, function($item, $key) use (& $result) {
    		if ($item->id == 174) {
    			array_unshift($result, $item);
    			unset($result[$key]);
    		}
    	});
        */

    	return $result;
    }

    public function get_cities($with_regions = FALSE)
    {
    	$key = 'asdcities_list_'.$this->id.'_'.intVal($with_regions);
    	if ( ! ($cities = Cache::instance('memcache')->get($key))) {
	    	$cities = array();
	    	foreach ($this->regions->order_by('name')->find_all() as $region) {
				if ($with_regions) {
					$load = & $cities[$region->name];
				} else {
					$load = & $cities;
				}
				foreach ($region->cities->find_all() as $city) {
					$load[$city->id] = $city->name;
				}

				asort($load);
	    	}

	    	$sort = ($with_regions === TRUE) ? 'ksort' : 'asort';
	    	$sort($cities);

	    	Cache::instance('memcache')->set_with_tags($key, $cities, NULL, array('cities', 'regions', 'countries'));
    	}

    	return $cities;
    }

}
