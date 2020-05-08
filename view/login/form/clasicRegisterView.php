  <form action="Login?action=newUser" method="post">
  <div class="form-group">
        <h3>Register</h3>
    </div>
  <div class="form-row">
    <div class="form-group col-md-6">
      <label for="new_email">Email</label>
      <input type="email" class="form-control" id="new_email" name="new_email">
    </div>
    <div class="form-group col-md-6">
      <label for="new_password">Password</label>
      <input type="password" class="form-control" id="new_password" name="new_password">
    </div>
    <div class="form-group col-md-12">
      <label for="new_name">Name</label>
      <input type="text" class="form-control" id="new_name" name="new_name">
    </div>
  </div>
  <div class="form-row">
    <div class="form-group col-md-6">
      <label for="new_code">Register Code</label>
      <input type="text" class="form-control" id="new_code" name="new_code">
    </div>
    <div class="form-group col-md-6">
      <label for="new_phone">Phone Number</label>
      <input type="text" class="form-control" id="new_phone" name="new_phone">
    </div>
  </div>
  <div class="form-group">
    <div class="form-check">
      <input class="form-check-input" type="checkbox" id="gridCheck">
      <label class="form-check-label" for="gridCheck">
        Accept Terms
      </label>
    </div>
  </div>
  <button type="submit" class="btn btn-primary">Join</button>
</form>