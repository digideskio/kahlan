<?php
namespace spec\matcher;

describe("toBeNull::match", function() {

	it("passes if null is null", function() {
		expect(null)->toBeNull();
	});

	it("fails if false is null", function() {
		expect(false)->not->toBeNull();
	});

	it("fails if [] is null", function() {
		expect([])->not->toBeNull();
	});

	it("fails if 0 is null", function() {
		expect(0)->not->toBeNull();
	});

	it("fails if '' is null", function() {
		expect('')->not->toBeNull();
	});

});

?>