<?php
/*
Gibbon, Flexible & Open School System
Copyright (C) 2010, Ross Parker

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program. If not, see <http://www.gnu.org/licenses/>.
*/

use Gibbon\Forms\Form;
use Gibbon\Forms\DatabaseFormFactory;

require_once __DIR__ . '/moduleFunctions.php';

$page->breadcrumbs
        ->add(__('Manage Approvers'), 'trips_manageApprovers.php')
        ->add(__('Add Approver'));

if (!isActionAccessible($guid, $connection2, '/modules/Trip Planner/trips_addApprover.php')) {
    //Acess denied
    $page->addError(__('You do not have access to this action.'));
} else {
    if (isset($_GET['return'])) {
        $editLink = null;
        if(isset($_GET['tripPlannerApproverID'])) {
            $editLink = $gibbon->session->get('absoluteURL') . '/index.php?q=/modules/' . $gibbon->session->get('module') . '/trips_editApprover.php&tripPlannerApproverID=' . $_GET['tripPlannerApproverID'];
        }
        returnProcess($guid, $_GET['return'], $editLink, null);
    }   

    $form = Form::create('addApprover', $gibbon->session->get('absoluteURL') . '/index.php?q=/modules/' . $gibbon->session->get('module') . '/trips_addApproverProcess.php');
    $form->setFactory(DatabaseFormFactory::create($pdo));
    $form->setTitle('Add Approver');

    $row = $form->addRow();
        $row->addLabel('gibbonPersonID', 'Staff');
        $row->addSelectStaff('gibbonPersonID')
            ->setRequired(true)
            ->placeholder('Please select...');

    $riskAssessmentApproval = getSettingByScope($connection2, 'Trip Planner', 'riskAssessmentApproval');
    if($riskAssessmentApproval) {
        $row = $form->addRow();
            $row->addLabel('finalApprover', 'Final Approver');
            $row->addCheckbox('finalApprover');
    }

    $row = $form->addRow();
        $row->addFooter();
        $row->addSubmit();

    print $form->getOutput();
}   
?>
