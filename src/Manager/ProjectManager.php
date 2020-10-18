<?php

namespace App\Manager;


use App\Entity\Project;


class ProjectManager 
{

    public function __construct(Project $project)
    {
        $this->project = $project;
    }

    public function getFormSetting(String $attribute){
       $enrollmentSettings =  $this->project->getEnrollmentSettings()['form'];
       
       if(isset($enrollmentSettings['attributes'][$attribute])){
           return true;
       }

       return false;
    }


}