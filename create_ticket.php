<div class="container main-wrap">
    <div class="add-new-ticket-class">
        <button onclick="DisplayNewTicket()" class="button button-primary">Add New Ticket</button>
    </div>
    
    <div id="add-new-ticket" style="display: none;">
        <h1> Create Ticket </h1>
        <div class="content-warp">
            <form>
				<div class="ticket-response" style="display: none;">
				</div>
                <div class="ticket-field-row">
                    <div class="main-row">
                        <div class="ticket-field-group">
                            <div class="ticket-field-label">
                                <label class="label">Contact *</label>
                            </div>
                            <div class="ticket-field-label">
                                <input type="email" class="form-control" id="email">
                            </div>
                        </div>
                    </div>
                    <div class="main-row">
                        <div class="ticket-field-group">
                            <div class="ticket-field-label">
                                <label class="label">Subject *</label>
                            </div>
                            <div class="ticket-field-label">
                                <input type="text" class="form-control" id="subject">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="ticket-field-row">
                    <div class="main-row">
                        <div class="ticket-field-group">
                            <div class="ticket-field-label">
                                <label class="label">Type</label>
                            </div>
                            <div class="ticket-field-label">
                                <select class="form-control" id="ticket_type">
									<option value="Question">Question</option>
                                    <option value="Incident">Incident</option>
                                    <option value="Problem">Problem</option>
                                    <option value="Feature Request">Feature Request</option>
                                    <option value="Refund">Refund</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="main-row">
                        <div class="ticket-field-group">
                            <div class="ticket-field-label">
                                <label class="label">Priority</label>
                            </div>
                            <div class="ticket-field-label">
                                <select class="form-control" id="ticket_priority">
                                    <option value="1">Low</option>
                                    <option value="2">Medium</option>
                                    <option value="3">High</option>
                                    <option value="4">Urgent</option>
                                </select>
                            </div> 
                        </div>
                    </div>
                </div>
                <div class="ticket-field-row">
                    <div class="main-row description">
                        <div class="ticket-field-group">
                            <div class="ticket-field-label">
                                <label class="label">Description *</label>
                            </div>
                            <div class="ticket-field-label">
                                <textarea id="description" class="form-control description-class"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="button-class">
                    <input type="button" onclick="create_ticket()" value="Save Changes" class="button button-primary">
                </div>
            </form>
            <div class="button-class close-add-new-ticket">
                <button class="button button-primary" onclick="HideNewTicketDiv()">Close</button>
            </div>
        </div>
    </div>
</div>