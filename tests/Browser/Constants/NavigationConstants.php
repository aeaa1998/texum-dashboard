<?php

namespace Tests\Browser\Constants;

class NavigationConstants {
	const TRIGGER_MOUSE_FUNCTIONS = "
	function triggerMouseEvent (node, eventType) {
		var clickEvent = document.createEvent ('MouseEvents');
		clickEvent.initEvent (eventType, true, true);
		node.dispatchEvent (clickEvent);
	}
	function triggerAll (node) {
		triggerMouseEvent(node, 'mouseover');
		triggerMouseEvent(node, 'mousedown');
		triggerMouseEvent(node, 'mouseup');
		triggerMouseEvent(node, 'click');
	}
	";

	const GET_ELEMENTS = [
		'id'    => "var node = document.getElementById('selector'); ",
		'name'  => "var node = document.getElementsByName('selector')[0]; ",
		'class' => "var nodes = document.getElementsByClassName('selector');
        var indexToShow = Object.keys(nodes).filter(function(key, index){
            return  window.getComputedStyle(nodes[key]).visibility !== 'hidden';
        });
        var node = nodes[indexToShow];
        ",
	];
	const KEY_UP_EVENT           = "var event = new KeyboardEvent('keyup', {'keyCode': 13, 'which':13});";
	const KEY_DOWN_EVENT         = "var event = new KeyboardEvent('keydown', {'keyCode': 13, 'which':13});";
	const KEY_PRESS_EVENT        = "var event = new KeyboardEvent('keypress', {'keyCode': 13, 'which':13});";
	const DISPATCH_EVENT_INPUT   = "input.dispatchEvent(event);";
	const SAFE_TRIGGER_ALL       = " if (node) {triggerAll(node)}";
	const SAFE_FOCUS             = " if (node) {node.focus();}";
	const HAS_CLASS              = " return node.classList.contains('className');";
	const FLATPICKR_FOCUS_FILTER = "node.parentElement.querySelector('.form-control', 'flatpick-input').focus();";
	const FLATPICKR_FOCUS        = "
			x = document.getElementsByClassName('uk-input uk-border-rounded flatpickr-input form-control input')[0];
            x.focus();";
	const CLASSNAME_FILTER_FOCUS = "
			let x = document.getElementsByClassName('selector');
            for (i = 0; i < x.length; i++) {
                if (x[i].text == 'secondSelector') {
                    x[i].focus();
                }
            }";

	public static function missingValueScript($missingSelector, $missingValue) {
		return "
		var x = document.getElementsByClassName('$missingSelector');
        var validKeys = Object.keys(x).filter(function(key, index){
            return  window.getComputedStyle(x[key]).visibility !== 'hidden';
        });
		var bool = validKeys.every(function(key, index) {
			if(x[key].value !== undefined){
				return x[key].value !== '$missingValue';
			}else{
				return x[key].textContent.trim() !== '$missingValue';
			}
		});
		return bool;
		";
	}
	public static function myClickScript($selector, $value, $type = 'normal') {
		//Type of click script
		if ($type == 'flatpickr-day') {
			return self::TRIGGER_MOUSE_FUNCTIONS . "
					let x = document.getElementsByClassName('$selector');
					for (i = 0; i < x.length; i++) {
                        var isVisible = window.getComputedStyle(x[i]).visibility !== 'hidden';
                        var isCurrentMonth = !x[i].classList.contains('prevMonthDay');
						if (x[i].textContent == '$value' && isCurrentMonth && isVisible) {
					    	triggerAll(x[i]);
					  	}
					}
					";
		} else if ($type == 'normal') {
			return self::TRIGGER_MOUSE_FUNCTIONS . "
					let x = document.getElementsByClassName('$selector');
					for (i = 0; i < x.length; i++) {
						if (x[i].textContent == '$value') {
					    	triggerAll(x[i]);
					  	}
					}
					";
		}
	}

	public static function myScript($type, $selector = null) {
		if ($type == 'v-select') {
			return self::TRIGGER_MOUSE_FUNCTIONS . "
	            var testContainer = document.querySelector('$selector');
	            triggerAll(testContainer.querySelector('.vs__open-indicator'));
			";
		} else if ($type == 'v-select-array') {
			return "
	        var testContainer = document.querySelector('$selector');
			var m = testContainer.querySelector('.vs__dropdown-menu').children;
			var array = []
			for (i = 0; i < m.length; i++){
			    array.push(m[i].textContent)
			}
			return array;
			";
		}
	}
	public static function setName($type, $parentId = null, $name = null) {
		if ($type == 'v-select') {
			return "
            var node = document.getElementById('$parentId');
            var input = node.firstElementChild.firstElementChild.querySelector('input');
            input.name = '$name';
            input.id = '$name';
            ";
		}
	}
	public static function eventDispatcher($type, $selector = null, $value = null) {
		if ($type == 'v-select') {
			return "var input = document.getElementsByName('$selector')[0];"
			. self::KEY_UP_EVENT . self::DISPATCH_EVENT_INPUT;
		}
	}
	public static function emulateClickScript($type, $selector) {
		$getElement = str_replace("selector", "$selector", self::GET_ELEMENTS[$type]);

		return self::TRIGGER_MOUSE_FUNCTIONS . $getElement . self::SAFE_TRIGGER_ALL;

	}
	public static function focusElementScript($type, $selector, $secondSelector = null) {
		if ($secondSelector !== null) {
			$getElement = str_replace("selector", "$selector", self::GET_ELEMENTS[$type]);

			return $getElement . self::SAFE_FOCUS;

		} else {
			$focusClassFilter = str_replace("selector", "$selector", self::CLASSNAME_FILTER_FOCUS);
			$focusClassFilter = str_replace("secondSelector", "$secondSelector", $focusClassFilter);

			return $focusClassFilter;
		}
	}
	public static function focusFlatPickrScript($selector) {
		if ($selector == !null) {
			$getElement = str_replace("selector", "$selector", self::GET_ELEMENTS['id']);

			return $getElement . self::FLATPICKR_FOCUS_FILTER;
		} else {
			return self::FLATPICKR_FOCUS;
		}
	}

	public static function elementHasClass($type, $selector, $className) {
		$getElement = str_replace("selector", "$selector", self::GET_ELEMENTS[$type]);
		$hasClass   = str_replace("className", "$className", self::HAS_CLASS);

		return $getElement . $hasClass;
	}
}
