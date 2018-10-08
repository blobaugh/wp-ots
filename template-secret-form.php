<form id="create-secret" autocomplete="off" method="post" action="">
	<input type="hidden" name="hello" value="hello world" />
	<textarea rows="7" class="btn-block" name="secret" autocomplete="off" placeholder="Secret content goes here..."></textarea>

	<br/><br/>
	Password (optional): <input class="input-block-level" type="text" name="password" id="passphraseField" value="" placeholder="A word or phrase that's difficult to guess" autocomplete="off">

	<br/> Lifetime
	<select name="expires" class="span3">
                <option value="604800">7 days</option>
                <option value="259200" selected>3 days</option>
                <option value="86400">1 day</option>
                <option value="43200">12 hours</option>
                <option value="14400">4 hours</option>
                <option value="3600">1 hour</option>
                <option value="1800">30 minutes</option>
                <option value="300">5 minutes</option>
	 </select>

	<br/><button class="create btn btn-large btn-block btn-custom cufon" type="submit" name="create" value="share">Create a secret link*</button>
</form>
