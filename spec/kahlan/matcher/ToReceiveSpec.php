<?php
namespace spec;

use kahlan\Arg;
use kahlan\analysis\Parser;
use kahlan\jit\patcher\Watcher;
use kahlan\spec\fixture\watcher\Foo;

describe("toReceive::match", function() {

	before(function() {
		if (!class_exists('kahlan\spec\fixture\watcher\Foo', false)) {
			$patcher = new Watcher();
			$file = file_get_contents('spec/fixture/watcher/Foo.php');
			eval('?>' . Parser::unparse($patcher->process(Parser::parse($file))));
		}

	});

	context("with dynamic call", function() {

		it("expects called method to be called", function() {
			$foo = new Foo();
			expect($foo)->toReceive('message');
			$foo->message();
		});

		it("expects static method called using non-static way to still called (PHP behavior)", function() {
			$foo = new Foo();
			expect($foo)->toReceive('::version');
			$foo->version();
		});

		it("expects static method called using non-static way to be not called on instance", function() {
			$foo = new Foo();
			expect($foo)->not->toReceive('version');
			$foo->version();
		});

		it("expects uncalled method to be uncalled", function() {
			$foo = new Foo();
			expect($foo)->not->toReceive('message');
		});

		context("when using with()", function() {

			it("expects called method to be called with correct params", function() {
				$foo = new Foo();
				expect($foo)->toReceive('message')->with('My Message', 'My Other Message');
				$foo->message('My Message', 'My Other Message');
			});

			it("expects called method with incorrect params to not be called", function() {
				$foo = new Foo();
				expect($foo)->not->toReceive('message')->with('My Message');
				$foo->message('Incorrect Message');
			});

			it("expects called method with missing params to not be called", function() {
				$foo = new Foo();
				expect($foo)->not->toReceive('message')->with('My Message');
				$foo->message();
			});

		});

		context("when using with() and matchers", function() {

			it("expects params match the toContain argument matcher", function() {
				$foo = new Foo();
				expect($foo)->toReceive('message')->with(Arg::toContain('My Message'));
				$foo->message(['My Message', 'My Other Message']);
			});

			it("expects params match the argument matchers", function() {
				$foo = new Foo();
				expect($foo)->toReceive('message')->with(Arg::toBeA('boolean'));
				expect($foo)->toReceiveNext('message')->with(Arg::toBeA('string'));
				$foo->message(true);
				$foo->message('Hello World');
			});

			it("expects params to not match the toContain argument matcher", function() {
				$foo = new Foo();
				expect($foo)->not->toReceive('message')->with(Arg::toContain('Message'));
				$foo->message(['My Message', 'My Other Message']);
			});

		});

		context("when using classname", function() {

			it("expects called method to be called", function() {
				$foo = new Foo();
				expect('kahlan\spec\fixture\watcher\Foo')->toReceive('message');
				$foo->message();
			});

			it("expects uncalled method to be uncalled", function() {
				$foo = new Foo();
				expect('kahlan\spec\fixture\watcher\Foo')->not->toReceive('message');
			});

			it("expects called method to be uncalled using a wrong classname", function() {
				$foo = new Foo();
				expect('kahlan\spec\fixture\watcher\FooFoo')->not->toReceive('message');
				$foo->message();
			});

		});
	});

	context("with static call", function() {

		it("expects called method to be called", function() {
			expect('kahlan\spec\fixture\watcher\Foo')->toReceive('::version');
			Foo::version();
		});

		it("expects called method to not be dynamically called", function() {
			expect('kahlan\spec\fixture\watcher\Foo')->not->toReceive('version');
			Foo::version();
		});

		it("expects called method on instance to be called on classname", function() {
			$foo = new Foo();
			expect('kahlan\spec\fixture\watcher\Foo')->toReceive('::version');
			$foo::version();
		});

		it("expects called method on instance to not be dynamically called", function() {
			$foo = new Foo();
			expect('kahlan\spec\fixture\watcher\Foo')->not->toReceive('version');
			$foo::version();
		});

		it("expects called method on instance to be called on classname (alternative syntax)", function() {
			$foo = new Foo();
			expect($foo)->toReceive('::version');
			$foo::version();
		});
	});

});

?>