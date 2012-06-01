<?php

/**
 * BaseCompany
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property string $name
 * @property Doctrine_Collection $Projects
 * 
 * @method string              getName()     Returns the current record's "name" value
 * @method Doctrine_Collection getProjects() Returns the current record's "Projects" collection
 * @method Company             setName()     Sets the current record's "name" value
 * @method Company             setProjects() Sets the current record's "Projects" collection
 * 
 * @package    sfSesame
 * @subpackage model
 * @author     Rober Martín H
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BaseCompany extends sfDoctrineRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('company');
        $this->hasColumn('name', 'string', 150, array(
             'type' => 'string',
             'notnull' => true,
             'unique' => true,
             'length' => 150,
             ));
    }

    public function setUp()
    {
        parent::setUp();
        $this->hasMany('Project as Projects', array(
             'local' => 'id',
             'foreign' => 'company_id'));

        $timestampable0 = new Doctrine_Template_Timestampable();
        $this->actAs($timestampable0);
    }
}