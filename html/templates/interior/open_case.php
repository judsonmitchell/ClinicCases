<nav class="case-tabs" data-caseid='<?php echo $id ?>'>
  <div class="nav nav-tabs" id="case<?php echo $id ?>Data" role="tablist">
    <button class="nav-link active" id="nav-<?php echo $id ?>-notes-tab" data-bs-toggle="tab" data-bs-target="#nav-<?php echo $id ?>-notes" type="button" role="tab" aria-controls="nav-<?php echo $id ?>-notes" aria-selected="true">Case Notes</button>
    <button class="nav-link" id="nav-<?php echo $id ?>-data-tab" data-bs-toggle="tab" data-bs-target="#nav-<?php echo $id ?>-data" type="button" role="tab" aria-controls="nav-<?php echo $id ?>-data" aria-selected="false">Case Data</button>
    <button class="nav-link" id="nav-<?php echo $id ?>-documents-tab" data-bs-toggle="tab" data-bs-target="#nav-<?php echo $id ?>-documents" type="button" role="tab" aria-controls="nav-<?php echo $id ?>-documents" aria-selected="false">Documents</button>
    <button class="nav-link" id="nav-<?php echo $id ?>-events-tab" data-bs-toggle="tab" data-bs-target="#nav-<?php echo $id ?>-events" type="button" role="tab" aria-controls="nav-<?php echo $id ?>-events" aria-selected="false">Events</button>
    <button class="nav-link" id="nav-<?php echo $id ?>-messages-tab" data-bs-toggle="tab" data-bs-target="#nav-<?php echo $id ?>-messages" type="button" role="tab" aria-controls="nav-<?php echo $id ?>-messages" aria-selected="false">Messages</button>
    <button class="nav-link" id="nav-<?php echo $id ?>-conflicts-tab" data-bs-toggle="tab" data-bs-target="#nav-<?php echo $id ?>-conflicts" type="button" role="tab" aria-controls="nav-<?php echo $id ?>-conflicts" aria-selected="false">Conflicts</button>
    <button class="nav-link" id="nav-<?php echo $id ?>-contacts-tab" data-bs-toggle="tab" data-bs-target="#nav-<?php echo $id ?>-contacts" type="button" role="tab" aria-controls="nav-<?php echo $id ?>-contacts" aria-selected="false">Contacts</button>

  </div>
  <div id="assignedUsersContainer">
    <div></div>
    <div id="addAssignedUser"></div>
  </div>
</nav>
<div class="tab-content" id="nav-<?php echo $id ?>-tabContent">
  <div class="tab-pane fade show active" id="nav-<?php echo $id ?>-notes" role="tabpanel" aria-labelledby="nav-<?php echo $id ?>-notes-tab">

  </div>
  <div class="tab-pane fade" id="nav-<?php echo $id ?>-data" role="tabpanel" aria-labelledby="nav-<?php echo $id ?>-data-tab">Case Data</div>
  <div class="tab-pane fade" id="nav-<?php echo $id ?>-documents" role="tabpanel" aria-labelledby="nav-<?php echo $id ?>-documents-tab">Documents</div>
  <div class="tab-pane fade" id="nav-<?php echo $id ?>-events" role="tabpanel" aria-labelledby="nav-<?php echo $id ?>-events-tab">Events</div>
  <div class="tab-pane fade" id="nav-<?php echo $id ?>-messages" role="tabpanel" aria-labelledby="nav-<?php echo $id ?>-messages-tab">Message</div>
  <div class="tab-pane fade" id="nav-<?php echo $id ?>-conflicts" role="tabpanel" aria-labelledby="nav-<?php echo $id ?>-conflicts-tab">Conflicts</div>
  <div class="tab-pane fade" id="nav-<?php echo $id ?>-contacts" role="tabpanel" aria-labelledby="nav-<?php echo $id ?>-contacts-tab">Contacts</div>

</div>