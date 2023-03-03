<div class="modal fade case-event" role="dialog" id="viewEventModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="viewEventLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <img data-target="#viewEventModal" src="html/ico/times_circle.svg" alt="" class="close_modal">

      <div class="modal-header">
        <div class="case-event__title">
          <h5 class="modal-title" id="viewEventLabel">
            <h2 class="event_task_title"></h2>

          </h5>
        </div>
      </div>
      <div class="modal-body">
        <div class="case-event__details">
          <div class="event-task-time">
            <div>
              <p><label><strong>Start:</strong></label>
                <span class="event_start"></span>
              </p>
              <p><label><strong>End:</strong></label>
                <span class="event_end"></span>
              </p>
            </div>

          </div>
          <p class="event-location location"><label><img src="html/ico/location.svg" alt=""></label>
            <span></span>
          </p>
          <p class="event-location guests"><label><img src="html/ico/guests.svg" alt=""></label>
            <span></span>
          </p>
          <div class="event_responsibles">

          </div>
          <div class="details_notes">
            <p><strong>Notes:</strong></p>
            <p>
              <span class="event_notes"><?php echo htmlentities($notes); ?></span>
            </p>
          </div>

        </div>
      </div>
    </div>
  </div>
</div>