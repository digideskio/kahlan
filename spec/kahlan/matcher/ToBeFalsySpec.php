<?php
namespace spec\matcher;

describe("toBeFalsy::match", function() {

	it("passes if false is fasly", function() {
		expect(false)->toBeFalsy();
	});

	it("passes if null is fasly", function() {
		expect(null)->toBeFalsy();
	});

	it("passes if [] is fasly", function() {
		expect([])->toBeFalsy();
	});

	it("passes if 0 is fasly", function() {
		expect(0)->toBeFalsy();
	});

	it("passes if '' is fasly", function() {
		expect('')->toBeFalsy();
	});

});

?>