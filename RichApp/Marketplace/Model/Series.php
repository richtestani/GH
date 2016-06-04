<?php
namespace Marketplace\Model;
use RichApp\Core\Model;
use RichApp\Model as Models;

class Series extends Model {
	
	protected $id;
	protected $page_id;
	protected $url;
	protected $rating;
	protected $website;
	
	public function __construct()
    {
		parent::__construct();
        $this->setTable('series');
        $this->properties = array(
            'id',
            'series',
			'about',
			'created_on',
			'created_by',
			'slug'
        );
    }
	
	public function findAllSeries()
	{
		$series = $this->findAll();
		return $series;
	}
	
	public function findSeriesBy($type, $id)
	{
		$sql = "SELECT pages.title, pages.body, pages.slug FROM $type LEFT JOIN pages ON ".$type.".page_id=pages.id WHERE ".$type.".series_id=:series_id AND pages.published=1 ORDER BY ".$type.".episode";

		$series = $this->getAll($sql, [':series_id'=>$id]);
		return $series;
	}
	
	public function getProperties()
    {
        return $this->properties;
    }
	
}