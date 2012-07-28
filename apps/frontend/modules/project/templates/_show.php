<?php
/* @var $project Project */
/* @var $company Company */
$company = $project->getCompany();
?>
<table class="table table-bordered">
    <tbody>
    <tr>
        <th>Name:</th>
        <td><?php echo $project->getName(); ?></td>
    </tr>
    <tr>
        <th>Company:</th>
        <td><?php echo $company->getName(); ?></td>
    </tr>
    </tbody>
</table>
