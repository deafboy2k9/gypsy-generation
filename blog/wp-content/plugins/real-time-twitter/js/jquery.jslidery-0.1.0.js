/**
 * @constructor
 */
jSlideryEffects = function()
{
	/**
	 * @private
	 */
	var _effects = {};

	/**
	 * @lends jSlidery
	 */
	return {

		/**
		 * @public
		 */
		add: function(label, effect)
		{
			_effects[label] = effect;
		},

		/**
		 * @public
		 */
		get: function(label)
		{
			return _effects[label];
		}
	};
}();

jSlideryEffects.add("show",
{
	processor: function(element, duration)
	{
		element.show();
	}
});
jSlideryEffects.add("hide",
{
	processor: function(element, duration)
	{
		element.hide();
	}
});
jSlideryEffects.add("fadeIn",
{
	processor: function(element, duration)
	{
		element.fadeIn(duration, function()
		{
	    	 // **** remove Opacity-Filter in ie ****
	    	if ($(this)[0].style.removeAttribute)
	    	{
	    		$(this)[0].style.removeAttribute('filter');
	    	}
	    });
	}
});
jSlideryEffects.add("fadeOut",
{
	processor: function(element, duration)
	{
		element.fadeOut(duration);
	}
});
jSlideryEffects.add("slideDown",
{
	processor: function(element, duration)
	{
		element.slideDown(duration);
	}
});
jSlideryEffects.add("slideUp",
{
	processor: function(element, duration)
	{
		element.slideUp(duration);
	}
});

(function($)
{
    $.fn.jSlidery = function(options)
    {
        return this.each(function()
        {
            jSlidery(this, options);
        });
    };
    jSlidery = function(container, options)
    {
        var settings =
        {
        	animation: 'fade',
            duration:  1500,
            delay:   4000,
            sequence:  'forward',
            selector:  null
        };

        if (options)
        {
        	$.extend(settings, options);
        }

        var elements = settings.children === null ? $(container).children(): $(container).children(settings.selector);

        if (elements.length > 1)
        {
            $(container).css('position', 'relative');

            for (var i = 0; i < elements.length; i++)
            {
                $(elements[i]).css('position', 'absolute').hide();
            };

            var v = initSequence(elements.length, settings);

    		processEffect(
    		{
    			element: $(elements[v[0]]),
    			effect: 'show',
    			duration: settings.duration
    		});

            goDelayed(elements, settings, v[0], v[1]);
		}
    };
    goDelayed = function(elements, settings, current, next)
    {
        setTimeout(function()
        {
            go(elements, settings, current, next);
        }, settings.delay);
    };
    go = function(elements, settings, current, next)
    {
    	var combi =
		{
    		'show': ['show', 'hide'],
    		'fade': ['fadeIn', 'fadeOut'],
    		'slide': ['slideDown', 'slideUp']
		};

    	var animation =
		{
    		out:
    		{
    			element: $(elements[current]),
    			effect: combi[settings.animation][1],
    			duration: settings.duration
    		},
    		_in:
			{
    			element: $(elements[next]),
    			effect: combi[settings.animation][0],
    			duration: settings.duration
			}
		};

    	processAnimation(animation);

    	current = next;
    	next = skipSequence(current, elements.length, settings.sequence);

        goDelayed(elements, settings, current, next);
    };
    processAnimation = function(animation)
    {
    	processEffect(animation.out);
    	processEffect(animation._in);
    };
    processEffect = function(effect)
    {
    	var e = jSlideryEffects.get(effect.effect);
    	if (e)
		{
    		e.processor(effect.element, effect.duration);
		}
    };
    initSequence = function(max, settings)
    {
        var current = 0;
        var next = 1;

        switch(settings.sequence)
        {
        	case 'forward':
        	default:
        		break;
        	case 'backward':
            	current = max - 1;
            	next = max - 2;
        		break;
        	case 'random':
            	current = getRandom(max);
            	next = getRandom(max, current);
        		break;
        	case 'random_forward':
            	settings.sequence = 'forward';
            	current = getRandom(max);
            	next = getNext(current, max);
        		break;
        	case 'random_backward':
            	settings.sequence = 'backward';
            	current = getRandom(max);
            	next = getPrevious(current, max);
        		break;
        }

        return [current, next];
    };
    skipSequence = function(val, max, sequence)
    {
        switch(sequence)
        {
        	case 'forward':
        	default:
        		val = getNext(val, max);
        		break;
        	case 'backward':
        		val = getPrevious(val, max);
        		break;
        	case 'random':
        		val = getRandom(max, val);
        		break;
        }

    	return val;
    };
    getNext = function(val, max)
    {
        return val < max - 1 ? ++val : 0;
    };
    getPrevious = function(val, max)
    {
        return val > 0 ? --val : max - 1;
    };
    getRandom = function(max, exclude)
    {
    	var n = Math.floor(Math.random() * max);

    	if (exclude)
		{
        	while (n == exclude)
        	{
        		n = getRandom(max);
        	}
		}

    	return n;
    };
})(jQuery);
