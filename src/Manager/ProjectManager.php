<?php

namespace App\Manager;


use App\Entity\Project;


class ProjectManager 
{
    private Project $project;

    public function __construct(Project $project)
    {
        $this->project = $project;
    }

    public function getFormSetting(string $attribute): bool
    {
       $enrollmentSettings = $this->project->getEnrollmentSettings();
       if (!is_array($enrollmentSettings)) {
           return false;
       }

       $formSettings = $enrollmentSettings['form'] ?? null;
       if (!is_array($formSettings)) {
           return false;
       }

       $attributes = $formSettings['attributes'] ?? null;
       if (!is_array($attributes)) {
           return false;
       }

       return isset($attributes[$attribute]) && $attributes[$attribute] === true;
    }


}
