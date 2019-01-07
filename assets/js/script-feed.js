


/*_____________________________________ Wookmark plugin_____________________________________________________________________*/
/*!
  jQuery Wookmark plugin
  @name jquery.wookmark.js
  @author Christoph Ono (chri@sto.ph or @gbks)
  @author Sebastian Helzle (sebastian@helzle.net or @sebobo)
  @version 1.4.6
  @date 12/07/2013
  @category jQuery plugin
  @copyright (c) 2009-2013 Christoph Ono (www.wookmark.com)
  @license Licensed under the MIT (http://www.opensource.org/licenses/mit-license.php) license.
*/
(function (factory) {
    if (typeof define === 'function' && define.amd)
        define(['jquery'], factory);
    else
        factory(jQuery);
}(function ($) {
    var Wookmark, defaultOptions, __bind;

    __bind = function(fn, me) {
        return function() {
            return fn.apply(me, arguments);
        };
    };

    // Wookmark default options
    defaultOptions = {
        align: 'center',
        autoResize: false,
        comparator: null,
        container: $('body'),
        direction: undefined,
        ignoreInactiveItems: true,
        itemWidth: 0,
        fillEmptySpace: false,
        flexibleWidth: 0,
        offset: 2,
        outerOffset: 0,
        onLayoutChanged: undefined,
        possibleFilters: [],
        resizeDelay: 50,
        verticalOffset: undefined
    };

    // Function for executing css writes to dom on the next animation frame if supported
    var executeNextFrame = window.requestAnimationFrame || function(callback) {callback();};

    function bulkUpdateCSS(data) {
        executeNextFrame(function() {
            var i, item;
            for (i = 0; i < data.length; i++) {
                item = data[i];
                item.obj.css(item.css);
            }
        });
    }

    function cleanFilterName(filterName) {
        return $.trim(filterName).toLowerCase();
    }

    // Main wookmark plugin class
    Wookmark = (function() {

        function Wookmark(handler, options) {
            // Instance variables.
            this.handler = handler;
            this.columns = this.containerWidth = this.resizeTimer = null;
            this.activeItemCount = 0;
            this.itemHeightsDirty = true;
            this.placeholders = [];

            $.extend(true, this, defaultOptions, options);

            this.verticalOffset = this.verticalOffset || this.offset;

            // Bind instance methods
            this.update = __bind(this.update, this);
            this.onResize = __bind(this.onResize, this);
            this.onRefresh = __bind(this.onRefresh, this);
            this.getItemWidth = __bind(this.getItemWidth, this);
            this.layout = __bind(this.layout, this);
            this.layoutFull = __bind(this.layoutFull, this);
            this.layoutColumns = __bind(this.layoutColumns, this);
            this.filter = __bind(this.filter, this);
            this.clear = __bind(this.clear, this);
            this.getActiveItems = __bind(this.getActiveItems, this);
            this.refreshPlaceholders = __bind(this.refreshPlaceholders, this);
            this.sortElements = __bind(this.sortElements, this);
            this.updateFilterClasses = __bind(this.updateFilterClasses, this);

            // Initial update of the filter classes
            this.updateFilterClasses();

            // Listen to resize event if requested.
            if (this.autoResize)
                $(window).bind('resize.wookmark', this.onResize);

            this.container.bind('refreshWookmark', this.onRefresh);
        }

        Wookmark.prototype.updateFilterClasses = function() {
            // Collect filter data
            var i = 0, j = 0, k = 0, filterClasses = {}, itemFilterClasses,
                $item, filterClass, possibleFilters = this.possibleFilters, possibleFilter;

            for (; i < this.handler.length; i++) {
                $item = this.handler.eq(i);

                // Read filter classes and globally store each filter class as object and the fitting items in the array
                itemFilterClasses = $item.data('filterClass');
                if (typeof itemFilterClasses == 'object' && itemFilterClasses.length > 0) {
                    for (j = 0; j < itemFilterClasses.length; j++) {
                        filterClass = cleanFilterName(itemFilterClasses[j]);

                        if (!filterClasses[filterClass]) {
                            filterClasses[filterClass] = [];
                        }
                        filterClasses[filterClass].push($item[0]);
                    }
                }
            }

            for (; k < possibleFilters.length; k++) {
                possibleFilter = cleanFilterName(possibleFilters[k]);
                if (!(possibleFilter in filterClasses)) {
                    filterClasses[possibleFilter] = [];
                }
            }

            this.filterClasses = filterClasses;
        };

        // Method for updating the plugins options
        Wookmark.prototype.update = function(options) {
            this.itemHeightsDirty = true;
            $.extend(true, this, options);
        };

        // This timer ensures that layout is not continuously called as window is being dragged.
        Wookmark.prototype.onResize = function() {
            clearTimeout(this.resizeTimer);
            this.itemHeightsDirty = this.flexibleWidth !== 0;
            this.resizeTimer = setTimeout(this.layout, this.resizeDelay);
        };

        // Marks the items heights as dirty and does a relayout
        Wookmark.prototype.onRefresh = function() {
            this.itemHeightsDirty = true;
            this.layout();
        };

        /**
         * Filters the active items with the given string filters.
         * @param filters array of string
         * @param mode 'or' or 'and'
         */
        Wookmark.prototype.filter = function(filters, mode) {
            var activeFilters = [], activeFiltersLength, activeItems = $(),
                i, j, k, filter;

            filters = filters || [];
            mode = mode || 'or';

            if (filters.length) {
                // Collect active filters
                for (i = 0; i < filters.length; i++) {
                    filter = cleanFilterName(filters[i]);
                    if (filter in this.filterClasses) {
                        activeFilters.push(this.filterClasses[filter]);
                    }
                }

                // Get items for active filters with the selected mode
                activeFiltersLength = activeFilters.length;
                if (mode == 'or' || activeFiltersLength == 1) {
                    // Set all items in all active filters active
                    for (i = 0; i < activeFiltersLength; i++) {
                        activeItems = activeItems.add(activeFilters[i]);
                    }
                } else if (mode == 'and') {
                    var shortestFilter = activeFilters[0],
                        itemValid = true, foundInFilter,
                        currentItem, currentFilter;

                    // Find shortest filter class
                    for (i = 1; i < activeFiltersLength; i++) {
                        if (activeFilters[i].length < shortestFilter.length) {
                            shortestFilter = activeFilters[i];
                        }
                    }

                    // Iterate over shortest filter and find elements in other filter classes
                    shortestFilter = shortestFilter || [];
                    for (i = 0; i < shortestFilter.length; i++) {
                        currentItem = shortestFilter[i];
                        itemValid = true;

                        for (j = 0; j < activeFilters.length && itemValid; j++) {
                            currentFilter = activeFilters[j];
                            if (shortestFilter == currentFilter) continue;

                            // Search for current item in each active filter class
                            for (k = 0, foundInFilter = false; k < currentFilter.length && !foundInFilter; k++) {
                                foundInFilter = currentFilter[k] == currentItem;
                            }
                            itemValid &= foundInFilter;
                        }
                        if (itemValid)
                            activeItems.push(shortestFilter[i]);
                    }
                }
                // Hide inactive items
                this.handler.not(activeItems).addClass('inactive');
            } else {
                // Show all items if no filter is selected
                activeItems = this.handler;
            }

            // Show active items
            activeItems.removeClass('inactive');

            // Unset columns and refresh grid for a full layout
            this.columns = null;
            this.layout();
        };

        /**
         * Creates or updates existing placeholders to create columns of even height
         */
        Wookmark.prototype.refreshPlaceholders = function(columnWidth, sideOffset) {
            var i = this.placeholders.length,
                $placeholder, $lastColumnItem,
                columnsLength = this.columns.length, column,
                height, top, innerOffset,
                containerHeight = this.container.innerHeight();

            for (; i < columnsLength; i++) {
                $placeholder = $('<div class="wookmark-placeholder"/>').appendTo(this.container);
                this.placeholders.push($placeholder);
            }

            innerOffset = this.offset + parseInt(this.placeholders[0].css('borderLeftWidth'), 10) * 2;

            for (i = 0; i < this.placeholders.length; i++) {
                $placeholder = this.placeholders[i];
                column = this.columns[i];

                if (i >= columnsLength || !column[column.length - 1]) {
                    $placeholder.css('display', 'none');
                } else {
                    $lastColumnItem = column[column.length - 1];
                    if (!$lastColumnItem) continue;
                    top = $lastColumnItem.data('wookmark-top') + $lastColumnItem.data('wookmark-height') + this.verticalOffset;
                    height = containerHeight - top - innerOffset;

                    $placeholder.css({
                        position: 'absolute',
                        display: height > 0 ? 'block' : 'none',
                        left: i * columnWidth + sideOffset,
                        top: top,
                        width: columnWidth - innerOffset,
                        height: height
                    });
                }
            }
        };

        // Method the get active items which are not disabled and visible
        Wookmark.prototype.getActiveItems = function() {
            return this.ignoreInactiveItems ? this.handler.not('.inactive') : this.handler;
        };

        // Method to get the standard item width
        Wookmark.prototype.getItemWidth = function() {
            var itemWidth = this.itemWidth,
                innerWidth = this.container.width() - 2 * this.outerOffset,
                firstElement = this.handler.eq(0),
                flexibleWidth = this.flexibleWidth;

            if (this.itemWidth === undefined || this.itemWidth === 0 && !this.flexibleWidth) {
                itemWidth = firstElement.outerWidth();
            }
            else if (typeof this.itemWidth == 'string' && this.itemWidth.indexOf('%') >= 0) {
                itemWidth = parseFloat(this.itemWidth) / 100 * innerWidth;
            }

            // Calculate flexible item width if option is set
            if (flexibleWidth) {
                if (typeof flexibleWidth == 'string' && flexibleWidth.indexOf('%') >= 0) {
                    flexibleWidth = parseFloat(flexibleWidth) / 100 * innerWidth;
                }

                // Find highest column count
                var paddedInnerWidth = (innerWidth + this.offset),
                    flexibleColumns = ~~(0.5 + paddedInnerWidth / (flexibleWidth + this.offset)),
                    fixedColumns = ~~(paddedInnerWidth / (itemWidth + this.offset)),
                    columns = Math.max(flexibleColumns, fixedColumns),
                    columnWidth = Math.min(flexibleWidth, ~~((innerWidth - (columns - 1) * this.offset) / columns));

                itemWidth = Math.max(itemWidth, columnWidth);

                // Stretch items to fill calculated width
                this.handler.css('width', itemWidth);
            }

            return itemWidth;
        };

        // Main layout method.
        Wookmark.prototype.layout = function(force) {
            // Do nothing if container isn't visible
            if (!this.container.is(':visible')) return;

            // Calculate basic layout parameters.
            var columnWidth = this.getItemWidth() + this.offset,
                containerWidth = this.container.width(),
                innerWidth = containerWidth - 2 * this.outerOffset,
                columns = ~~((innerWidth + this.offset) / columnWidth),
                offset = 0, maxHeight = 0, i = 0,
                activeItems = this.getActiveItems(),
                activeItemsLength = activeItems.length,
                $item;

            // Cache item height
            if (this.itemHeightsDirty || !this.container.data('itemHeightsInitialized')) {
                for (; i < activeItemsLength; i++) {
                    $item = activeItems.eq(i);
                    $item.data('wookmark-height', $item.outerHeight());
                }
                this.itemHeightsDirty = false;
                this.container.data('itemHeightsInitialized', true);
            }

            // Use less columns if there are to few items
            columns = Math.max(1, Math.min(columns, activeItemsLength));

            // Calculate the offset based on the alignment of columns to the parent container
            offset = this.outerOffset;
            if (this.align == 'center') {
                offset += ~~(0.5 + (innerWidth - (columns * columnWidth - this.offset)) >> 1);
            }

            // Get direction for positioning
            this.direction = this.direction || (this.align == 'right' ? 'right' : 'left');

            // If container and column count hasn't changed, we can only update the columns.
            if (!force && this.columns !== null && this.columns.length == columns && this.activeItemCount == activeItemsLength) {
                maxHeight = this.layoutColumns(columnWidth, offset);
            } else {
                maxHeight = this.layoutFull(columnWidth, columns, offset);
            }
            this.activeItemCount = activeItemsLength;

            // Set container height to height of the grid.
            this.container.css('height', maxHeight);

            // Update placeholders
            if (this.fillEmptySpace) {
                this.refreshPlaceholders(columnWidth, offset);
            }

            if (this.onLayoutChanged !== undefined && typeof this.onLayoutChanged === 'function') {
                this.onLayoutChanged();
            }
        };

        /**
         * Sort elements with configurable comparator
         */
        Wookmark.prototype.sortElements = function(elements) {
            return typeof(this.comparator) === 'function' ? elements.sort(this.comparator) : elements;
        };

        /**
         * Perform a full layout update.
         */
        Wookmark.prototype.layoutFull = function(columnWidth, columns, offset) {
            var $item, i = 0, k = 0,
                activeItems = $.makeArray(this.getActiveItems()),
                length = activeItems.length,
                shortest = null, shortestIndex = null,
                sideOffset, heights = [], itemBulkCSS = [],
                leftAligned = this.align == 'left' ? true : false;

            this.columns = [];

            // Sort elements before layouting
            activeItems = this.sortElements(activeItems);

            // Prepare arrays to store height of columns and items.
            while (heights.length < columns) {
                heights.push(this.outerOffset);
                this.columns.push([]);
            }

            // Loop over items.
            for (; i < length; i++ ) {
                $item = $(activeItems[i]);

                // Find the shortest column.
                shortest = heights[0];
                shortestIndex = 0;
                for (k = 0; k < columns; k++) {
                    if (heights[k] < shortest) {
                        shortest = heights[k];
                        shortestIndex = k;
                    }
                }
                $item.data('wookmark-top', shortest);

                // stick to left side if alignment is left and this is the first column
                sideOffset = offset;
                if (shortestIndex > 0 || !leftAligned)
                    sideOffset += shortestIndex * columnWidth;

                // Position the item.
                (itemBulkCSS[i] = {
                    obj: $item,
                    css: {
                        position: 'absolute',
                        top: shortest
                    }
                }).css[this.direction] = sideOffset;

                // Update column height and store item in shortest column
                heights[shortestIndex] += $item.data('wookmark-height') + this.verticalOffset;
                this.columns[shortestIndex].push($item);
            }

            bulkUpdateCSS(itemBulkCSS);

            // Return longest column
            return Math.max.apply(Math, heights);
        };

        /**
         * This layout method only updates the vertical position of the
         * existing column assignments.
         */
        Wookmark.prototype.layoutColumns = function(columnWidth, offset) {
            var heights = [], itemBulkCSS = [],
                i = 0, k = 0, j = 0, currentHeight,
                column, $item, itemData, sideOffset;

            for (; i < this.columns.length; i++) {
                heights.push(this.outerOffset);
                column = this.columns[i];
                sideOffset = i * columnWidth + offset;
                currentHeight = heights[i];

                for (k = 0; k < column.length; k++, j++) {
                    $item = column[k].data('wookmark-top', currentHeight);
                    (itemBulkCSS[j] = {
                        obj: $item,
                        css: {
                            top: currentHeight
                        }
                    }).css[this.direction] = sideOffset;

                    currentHeight += $item.data('wookmark-height') + this.verticalOffset;
                }
                heights[i] = currentHeight;
            }

            bulkUpdateCSS(itemBulkCSS);

            // Return longest column
            return Math.max.apply(Math, heights);
        };

        /**
         * Clear event listeners and time outs and the instance itself
         */
        Wookmark.prototype.clear = function() {
            clearTimeout(this.resizeTimer);
            $(window).unbind('resize.wookmark', this.onResize);
            this.container.unbind('refreshWookmark', this.onRefresh);
            this.handler.wookmarkInstance = null;
        };

        return Wookmark;
    })();

    $.fn.wookmark = function(options) {
        // Create a wookmark instance if not available
        if (!this.wookmarkInstance) {
            this.wookmarkInstance = new Wookmark(this, options || {});
        } else {
            this.wookmarkInstance.update(options || {});
        }

        // Apply layout
        this.wookmarkInstance.layout(true);

        // Display items (if hidden) and return jQuery object to maintain chainability
        return this.show();
    };
}));



/*________________________________________imagesLoaded__________________________________________________________________*/

/*!
 * imagesLoaded PACKAGED v3.0.4
 * JavaScript is all like "You images are done yet or what?"
 */

/*!
 * EventEmitter v4.2.0 - git.io/ee
 * Oliver Caldwell
 * MIT license
 * @preserve
 */

(function () {
    // Place the script in strict mode
    'use strict';

    /**
     * Class for managing events.
     * Can be extended to provide event functionality in other classes.
     *
     * @class EventEmitter Manages event registering and emitting.
     */
    function EventEmitter() {}

    // Shortcuts to improve speed and size

    // Easy access to the prototype
    var proto = EventEmitter.prototype;

    /**
     * Finds the index of the listener for the event in it's storage array.
     *
     * @param {Function[]} listeners Array of listeners to search through.
     * @param {Function} listener Method to look for.
     * @return {Number} Index of the specified listener, -1 if not found
     * @api private
     */
    function indexOfListener(listeners, listener) {
        var i = listeners.length;
        while (i--) {
            if (listeners[i].listener === listener) {
                return i;
            }
        }

        return -1;
    }

    /**
     * Returns the listener array for the specified event.
     * Will initialise the event object and listener arrays if required.
     * Will return an object if you use a regex search. The object contains keys for each matched event. So /ba[rz]/ might return an object containing bar and baz. But only if you have either defined them with defineEvent or added some listeners to them.
     * Each property in the object response is an array of listener functions.
     *
     * @param {String|RegExp} evt Name of the event to return the listeners from.
     * @return {Function[]|Object} All listener functions for the event.
     */
    proto.getListeners = function getListeners(evt) {
        var events = this._getEvents();
        var response;
        var key;

        // Return a concatenated array of all matching events if
        // the selector is a regular expression.
        if (typeof evt === 'object') {
            response = {};
            for (key in events) {
                if (events.hasOwnProperty(key) && evt.test(key)) {
                    response[key] = events[key];
                }
            }
        }
        else {
            response = events[evt] || (events[evt] = []);
        }

        return response;
    };

    /**
     * Takes a list of listener objects and flattens it into a list of listener functions.
     *
     * @param {Object[]} listeners Raw listener objects.
     * @return {Function[]} Just the listener functions.
     */
    proto.flattenListeners = function flattenListeners(listeners) {
        var flatListeners = [];
        var i;

        for (i = 0; i < listeners.length; i += 1) {
            flatListeners.push(listeners[i].listener);
        }

        return flatListeners;
    };

    /**
     * Fetches the requested listeners via getListeners but will always return the results inside an object. This is mainly for internal use but others may find it useful.
     *
     * @param {String|RegExp} evt Name of the event to return the listeners from.
     * @return {Object} All listener functions for an event in an object.
     */
    proto.getListenersAsObject = function getListenersAsObject(evt) {
        var listeners = this.getListeners(evt);
        var response;

        if (listeners instanceof Array) {
            response = {};
            response[evt] = listeners;
        }

        return response || listeners;
    };

    /**
     * Adds a listener function to the specified event.
     * The listener will not be added if it is a duplicate.
     * If the listener returns true then it will be removed after it is called.
     * If you pass a regular expression as the event name then the listener will be added to all events that match it.
     *
     * @param {String|RegExp} evt Name of the event to attach the listener to.
     * @param {Function} listener Method to be called when the event is emitted. If the function returns true then it will be removed after calling.
     * @return {Object} Current instance of EventEmitter for chaining.
     */
    proto.addListener = function addListener(evt, listener) {
        var listeners = this.getListenersAsObject(evt);
        var listenerIsWrapped = typeof listener === 'object';
        var key;

        for (key in listeners) {
            if (listeners.hasOwnProperty(key) && indexOfListener(listeners[key], listener) === -1) {
                listeners[key].push(listenerIsWrapped ? listener : {
                    listener: listener,
                    once: false
                });
            }
        }

        return this;
    };

    /**
     * Alias of addListener
     */
    proto.on = proto.addListener;

    /**
     * Semi-alias of addListener. It will add a listener that will be
     * automatically removed after it's first execution.
     *
     * @param {String|RegExp} evt Name of the event to attach the listener to.
     * @param {Function} listener Method to be called when the event is emitted. If the function returns true then it will be removed after calling.
     * @return {Object} Current instance of EventEmitter for chaining.
     */
    proto.addOnceListener = function addOnceListener(evt, listener) {
        return this.addListener(evt, {
            listener: listener,
            once: true
        });
    };

    /**
     * Alias of addOnceListener.
     */
    proto.once = proto.addOnceListener;

    /**
     * Defines an event name. This is required if you want to use a regex to add a listener to multiple events at once. If you don't do this then how do you expect it to know what event to add to? Should it just add to every possible match for a regex? No. That is scary and bad.
     * You need to tell it what event names should be matched by a regex.
     *
     * @param {String} evt Name of the event to create.
     * @return {Object} Current instance of EventEmitter for chaining.
     */
    proto.defineEvent = function defineEvent(evt) {
        this.getListeners(evt);
        return this;
    };

    /**
     * Uses defineEvent to define multiple events.
     *
     * @param {String[]} evts An array of event names to define.
     * @return {Object} Current instance of EventEmitter for chaining.
     */
    proto.defineEvents = function defineEvents(evts) {
        for (var i = 0; i < evts.length; i += 1) {
            this.defineEvent(evts[i]);
        }
        return this;
    };

    /**
     * Removes a listener function from the specified event.
     * When passed a regular expression as the event name, it will remove the listener from all events that match it.
     *
     * @param {String|RegExp} evt Name of the event to remove the listener from.
     * @param {Function} listener Method to remove from the event.
     * @return {Object} Current instance of EventEmitter for chaining.
     */
    proto.removeListener = function removeListener(evt, listener) {
        var listeners = this.getListenersAsObject(evt);
        var index;
        var key;

        for (key in listeners) {
            if (listeners.hasOwnProperty(key)) {
                index = indexOfListener(listeners[key], listener);

                if (index !== -1) {
                    listeners[key].splice(index, 1);
                }
            }
        }

        return this;
    };

    /**
     * Alias of removeListener
     */
    proto.off = proto.removeListener;

    /**
     * Adds listeners in bulk using the manipulateListeners method.
     * If you pass an object as the second argument you can add to multiple events at once. The object should contain key value pairs of events and listeners or listener arrays. You can also pass it an event name and an array of listeners to be added.
     * You can also pass it a regular expression to add the array of listeners to all events that match it.
     * Yeah, this function does quite a bit. That's probably a bad thing.
     *
     * @param {String|Object|RegExp} evt An event name if you will pass an array of listeners next. An object if you wish to add to multiple events at once.
     * @param {Function[]} [listeners] An optional array of listener functions to add.
     * @return {Object} Current instance of EventEmitter for chaining.
     */
    proto.addListeners = function addListeners(evt, listeners) {
        // Pass through to manipulateListeners
        return this.manipulateListeners(false, evt, listeners);
    };

    /**
     * Removes listeners in bulk using the manipulateListeners method.
     * If you pass an object as the second argument you can remove from multiple events at once. The object should contain key value pairs of events and listeners or listener arrays.
     * You can also pass it an event name and an array of listeners to be removed.
     * You can also pass it a regular expression to remove the listeners from all events that match it.
     *
     * @param {String|Object|RegExp} evt An event name if you will pass an array of listeners next. An object if you wish to remove from multiple events at once.
     * @param {Function[]} [listeners] An optional array of listener functions to remove.
     * @return {Object} Current instance of EventEmitter for chaining.
     */
    proto.removeListeners = function removeListeners(evt, listeners) {
        // Pass through to manipulateListeners
        return this.manipulateListeners(true, evt, listeners);
    };

    /**
     * Edits listeners in bulk. The addListeners and removeListeners methods both use this to do their job. You should really use those instead, this is a little lower level.
     * The first argument will determine if the listeners are removed (true) or added (false).
     * If you pass an object as the second argument you can add/remove from multiple events at once. The object should contain key value pairs of events and listeners or listener arrays.
     * You can also pass it an event name and an array of listeners to be added/removed.
     * You can also pass it a regular expression to manipulate the listeners of all events that match it.
     *
     * @param {Boolean} remove True if you want to remove listeners, false if you want to add.
     * @param {String|Object|RegExp} evt An event name if you will pass an array of listeners next. An object if you wish to add/remove from multiple events at once.
     * @param {Function[]} [listeners] An optional array of listener functions to add/remove.
     * @return {Object} Current instance of EventEmitter for chaining.
     */
    proto.manipulateListeners = function manipulateListeners(remove, evt, listeners) {
        var i;
        var value;
        var single = remove ? this.removeListener : this.addListener;
        var multiple = remove ? this.removeListeners : this.addListeners;

        // If evt is an object then pass each of it's properties to this method
        if (typeof evt === 'object' && !(evt instanceof RegExp)) {
            for (i in evt) {
                if (evt.hasOwnProperty(i) && (value = evt[i])) {
                    // Pass the single listener straight through to the singular method
                    if (typeof value === 'function') {
                        single.call(this, i, value);
                    }
                    else {
                        // Otherwise pass back to the multiple function
                        multiple.call(this, i, value);
                    }
                }
            }
        }
        else {
            // So evt must be a string
            // And listeners must be an array of listeners
            // Loop over it and pass each one to the multiple method
            i = listeners.length;
            while (i--) {
                single.call(this, evt, listeners[i]);
            }
        }

        return this;
    };

    /**
     * Removes all listeners from a specified event.
     * If you do not specify an event then all listeners will be removed.
     * That means every event will be emptied.
     * You can also pass a regex to remove all events that match it.
     *
     * @param {String|RegExp} [evt] Optional name of the event to remove all listeners for. Will remove from every event if not passed.
     * @return {Object} Current instance of EventEmitter for chaining.
     */
    proto.removeEvent = function removeEvent(evt) {
        var type = typeof evt;
        var events = this._getEvents();
        var key;

        // Remove different things depending on the state of evt
        if (type === 'string') {
            // Remove all listeners for the specified event
            delete events[evt];
        }
        else if (type === 'object') {
            // Remove all events matching the regex.
            for (key in events) {
                if (events.hasOwnProperty(key) && evt.test(key)) {
                    delete events[key];
                }
            }
        }
        else {
            // Remove all listeners in all events
            delete this._events;
        }

        return this;
    };

    /**
     * Emits an event of your choice.
     * When emitted, every listener attached to that event will be executed.
     * If you pass the optional argument array then those arguments will be passed to every listener upon execution.
     * Because it uses `apply`, your array of arguments will be passed as if you wrote them out separately.
     * So they will not arrive within the array on the other side, they will be separate.
     * You can also pass a regular expression to emit to all events that match it.
     *
     * @param {String|RegExp} evt Name of the event to emit and execute listeners for.
     * @param {Array} [args] Optional array of arguments to be passed to each listener.
     * @return {Object} Current instance of EventEmitter for chaining.
     */
    proto.emitEvent = function emitEvent(evt, args) {
        var listeners = this.getListenersAsObject(evt);
        var listener;
        var i;
        var key;
        var response;

        for (key in listeners) {
            if (listeners.hasOwnProperty(key)) {
                i = listeners[key].length;

                while (i--) {
                    // If the listener returns true then it shall be removed from the event
                    // The function is executed either with a basic call or an apply if there is an args array
                    listener = listeners[key][i];
                    response = listener.listener.apply(this, args || []);
                    if (response === this._getOnceReturnValue() || listener.once === true) {
                        this.removeListener(evt, listeners[key][i].listener);
                    }
                }
            }
        }

        return this;
    };

    /**
     * Alias of emitEvent
     */
    proto.trigger = proto.emitEvent;

    /**
     * Subtly different from emitEvent in that it will pass its arguments on to the listeners, as opposed to taking a single array of arguments to pass on.
     * As with emitEvent, you can pass a regex in place of the event name to emit to all events that match it.
     *
     * @param {String|RegExp} evt Name of the event to emit and execute listeners for.
     * @param {...*} Optional additional arguments to be passed to each listener.
     * @return {Object} Current instance of EventEmitter for chaining.
     */
    proto.emit = function emit(evt) {
        var args = Array.prototype.slice.call(arguments, 1);
        return this.emitEvent(evt, args);
    };

    /**
     * Sets the current value to check against when executing listeners. If a
     * listeners return value matches the one set here then it will be removed
     * after execution. This value defaults to true.
     *
     * @param {*} value The new value to check for when executing listeners.
     * @return {Object} Current instance of EventEmitter for chaining.
     */
    proto.setOnceReturnValue = function setOnceReturnValue(value) {
        this._onceReturnValue = value;
        return this;
    };

    /**
     * Fetches the current value to check against when executing listeners. If
     * the listeners return value matches this one then it should be removed
     * automatically. It will return true by default.
     *
     * @return {*|Boolean} The current value to check for or the default, true.
     * @api private
     */
    proto._getOnceReturnValue = function _getOnceReturnValue() {
        if (this.hasOwnProperty('_onceReturnValue')) {
            return this._onceReturnValue;
        }
        else {
            return true;
        }
    };

    /**
     * Fetches the events object and creates one if required.
     *
     * @return {Object} The events storage object.
     * @api private
     */
    proto._getEvents = function _getEvents() {
        return this._events || (this._events = {});
    };

    // Expose the class either via AMD, CommonJS or the global object
    if (typeof define === 'function' && define.amd) {
        // --------------------------------------------------
        // ADDED A MODULE ID
        // --------------------------------------------------
        define("eventEmitter/EventEmitter", function () {
            return EventEmitter;
        });
    }
    else if (typeof module !== 'undefined' && module.exports){
        module.exports = EventEmitter;
    }
    else {
        this.EventEmitter = EventEmitter;
    }
}.call(this));

/*!
 * eventie v1.0.3
 * event binding helper
 *   eventie.bind( elem, 'click', myFn )
 *   eventie.unbind( elem, 'click', myFn )
 */

/*jshint browser: true, undef: true, unused: true */
/*global define: false */

( function( window ) {

    'use strict';

    var docElem = document.documentElement;

    var bind = function() {};

    if ( docElem.addEventListener ) {
        bind = function( obj, type, fn ) {
            obj.addEventListener( type, fn, false );
        };
    } else if ( docElem.attachEvent ) {
        bind = function( obj, type, fn ) {
            obj[ type + fn ] = fn.handleEvent ?
                function() {
                    var event = window.event;
                    // add event.target
                    event.target = event.target || event.srcElement;
                    fn.handleEvent.call( fn, event );
                } :
                function() {
                    var event = window.event;
                    // add event.target
                    event.target = event.target || event.srcElement;
                    fn.call( obj, event );
                };
            obj.attachEvent( "on" + type, obj[ type + fn ] );
        };
    }

    var unbind = function() {};

    if ( docElem.removeEventListener ) {
        unbind = function( obj, type, fn ) {
            obj.removeEventListener( type, fn, false );
        };
    } else if ( docElem.detachEvent ) {
        unbind = function( obj, type, fn ) {
            obj.detachEvent( "on" + type, obj[ type + fn ] );
            try {
                delete obj[ type + fn ];
            } catch ( err ) {
                // can't delete window object properties
                obj[ type + fn ] = undefined;
            }
        };
    }

    var eventie = {
        bind: bind,
        unbind: unbind
    };

// transport
    if ( typeof define === 'function' && define.amd ) {
        // AMD
        // --------------------------------------------------
        // ADDED A MODULE ID
        // --------------------------------------------------
        define("eventie/eventie", eventie );
    } else {
        // browser global
        window.eventie = eventie;
    }

})( this );

/*!
 * imagesLoaded v3.0.4
 * JavaScript is all like "You images are done yet or what?"
 */

( function( window ) {

    'use strict';

    var $ = window.jQuery;
    var console = window.console;
    var hasConsole = typeof console !== 'undefined';

// -------------------------- helpers -------------------------- //

// extend objects
    function extend( a, b ) {
        for ( var prop in b ) {
            a[ prop ] = b[ prop ];
        }
        return a;
    }

    var objToString = Object.prototype.toString;
    function isArray( obj ) {
        return objToString.call( obj ) === '[object Array]';
    }

// turn element or nodeList into an array
    function makeArray( obj ) {
        var ary = [];
        if ( isArray( obj ) ) {
            // use object if already an array
            ary = obj;
        } else if ( typeof obj.length === 'number' ) {
            // convert nodeList to array
            for ( var i=0, len = obj.length; i < len; i++ ) {
                ary.push( obj[i] );
            }
        } else {
            // array of single index
            ary.push( obj );
        }
        return ary;
    }

// --------------------------  -------------------------- //

    function defineImagesLoaded( EventEmitter, eventie ) {

        /**
         * @param {Array, Element, NodeList, String} elem
         * @param {Object or Function} options - if function, use as callback
         * @param {Function} onAlways - callback function
         */
        function ImagesLoaded( elem, options, onAlways ) {
            // coerce ImagesLoaded() without new, to be new ImagesLoaded()
            if ( !( this instanceof ImagesLoaded ) ) {
                return new ImagesLoaded( elem, options );
            }
            // use elem as selector string
            if ( typeof elem === 'string' ) {
                elem = document.querySelectorAll( elem );
            }

            this.elements = makeArray( elem );
            this.options = extend( {}, this.options );

            if ( typeof options === 'function' ) {
                onAlways = options;
            } else {
                extend( this.options, options );
            }

            if ( onAlways ) {
                this.on( 'always', onAlways );
            }

            this.getImages();

            if ( $ ) {
                // add jQuery Deferred object
                this.jqDeferred = new $.Deferred();
            }

            // HACK check async to allow time to bind listeners
            var _this = this;
            setTimeout( function() {
                _this.check();
            });
        }

        ImagesLoaded.prototype = new EventEmitter();

        ImagesLoaded.prototype.options = {};

        ImagesLoaded.prototype.getImages = function() {
            this.images = [];

            // filter & find items if we have an item selector
            for ( var i=0, len = this.elements.length; i < len; i++ ) {
                var elem = this.elements[i];
                // filter siblings
                if ( elem.nodeName === 'IMG' ) {
                    this.addImage( elem );
                }
                // find children
                var childElems = elem.querySelectorAll('img');
                // concat childElems to filterFound array
                for ( var j=0, jLen = childElems.length; j < jLen; j++ ) {
                    var img = childElems[j];
                    this.addImage( img );
                }
            }
        };

        /**
         * @param {Image} img
         */
        ImagesLoaded.prototype.addImage = function( img ) {
            var loadingImage = new LoadingImage( img );
            this.images.push( loadingImage );
        };

        ImagesLoaded.prototype.check = function() {
            var _this = this;
            var checkedCount = 0;
            var length = this.images.length;
            this.hasAnyBroken = false;
            // complete if no images
            if ( !length ) {
                this.complete();
                return;
            }

            function onConfirm( image, message ) {
                if ( _this.options.debug && hasConsole ) {
                    console.log( 'confirm', image, message );
                }

                _this.progress( image );
                checkedCount++;
                if ( checkedCount === length ) {
                    _this.complete();
                }
                return true; // bind once
            }

            for ( var i=0; i < length; i++ ) {
                var loadingImage = this.images[i];
                loadingImage.on( 'confirm', onConfirm );
                loadingImage.check();
            }
        };

        ImagesLoaded.prototype.progress = function( image ) {
            this.hasAnyBroken = this.hasAnyBroken || !image.isLoaded;
            // HACK - Chrome triggers event before object properties have changed. #83
            var _this = this;
            setTimeout( function() {
                _this.emit( 'progress', _this, image );
                if ( _this.jqDeferred ) {
                    _this.jqDeferred.notify( _this, image );
                }
            });
        };

        ImagesLoaded.prototype.complete = function() {
            var eventName = this.hasAnyBroken ? 'fail' : 'done';
            this.isComplete = true;
            var _this = this;
            // HACK - another setTimeout so that confirm happens after progress
            setTimeout( function() {
                _this.emit( eventName, _this );
                _this.emit( 'always', _this );
                if ( _this.jqDeferred ) {
                    var jqMethod = _this.hasAnyBroken ? 'reject' : 'resolve';
                    _this.jqDeferred[ jqMethod ]( _this );
                }
            });
        };

        // -------------------------- jquery -------------------------- //

        if ( $ ) {
            $.fn.imagesLoaded = function( options, callback ) {
                var instance = new ImagesLoaded( this, options, callback );
                return instance.jqDeferred.promise( $(this) );
            };
        }


        // --------------------------  -------------------------- //

        var cache = {};

        function LoadingImage( img ) {
            this.img = img;
        }

        LoadingImage.prototype = new EventEmitter();

        LoadingImage.prototype.check = function() {
            // first check cached any previous images that have same src
            var cached = cache[ this.img.src ];
            if ( cached ) {
                this.useCached( cached );
                return;
            }
            // add this to cache
            cache[ this.img.src ] = this;

            // If complete is true and browser supports natural sizes,
            // try to check for image status manually.
            if ( this.img.complete && this.img.naturalWidth !== undefined ) {
                // report based on naturalWidth
                this.confirm( this.img.naturalWidth !== 0, 'naturalWidth' );
                return;
            }

            // If none of the checks above matched, simulate loading on detached element.
            var proxyImage = this.proxyImage = new Image();
            eventie.bind( proxyImage, 'load', this );
            eventie.bind( proxyImage, 'error', this );
            proxyImage.src = this.img.src;
        };

        LoadingImage.prototype.useCached = function( cached ) {
            if ( cached.isConfirmed ) {
                this.confirm( cached.isLoaded, 'cached was confirmed' );
            } else {
                var _this = this;
                cached.on( 'confirm', function( image ) {
                    _this.confirm( image.isLoaded, 'cache emitted confirmed' );
                    return true; // bind once
                });
            }
        };

        LoadingImage.prototype.confirm = function( isLoaded, message ) {
            this.isConfirmed = true;
            this.isLoaded = isLoaded;
            this.emit( 'confirm', this, message );
        };

        // trigger specified handler for event type
        LoadingImage.prototype.handleEvent = function( event ) {
            var method = 'on' + event.type;
            if ( this[ method ] ) {
                this[ method ]( event );
            }
        };

        LoadingImage.prototype.onload = function() {
            this.confirm( true, 'onload' );
            this.unbindProxyEvents();
        };

        LoadingImage.prototype.onerror = function() {
            this.confirm( false, 'onerror' );
            this.unbindProxyEvents();
        };

        LoadingImage.prototype.unbindProxyEvents = function() {
            eventie.unbind( this.proxyImage, 'load', this );
            eventie.unbind( this.proxyImage, 'error', this );
        };

        // -----  ----- //

        return ImagesLoaded;
    }

// -------------------------- transport -------------------------- //

    if ( typeof define === 'function' && define.amd ) {
        // AMD
        // --------------------------------------------------
        // ADDED A MODULE ID
        // --------------------------------------------------
        define("imagesLoaded", [
                'eventEmitter/EventEmitter',
                'eventie/eventie'
            ],
            defineImagesLoaded );
    } else {
        // browser global
        window.imagesLoaded = defineImagesLoaded(
            window.EventEmitter,
            window.eventie
        );
    }

})( window );



/*________________________________________script__________________________________________________________________*/





(function ($){
    var $tiles = $('#tiles'),
        $handler = $('li', $tiles),
        $main = $('#main'),
        $window = $(window),
        $document = $(document),
        options = {
            autoResize: true, // This will auto-update the layout when the browser window is resized.
            container: $main, // Optional, used for some extra CSS styling
            offset: 20, // Optional, the distance between grid items
            itemWidth:280 // Optional, the width of a grid item
        };
    /**
     * Reinitializes the wookmark handler after all images have loaded
     */
    function applyLayout() {
        $tiles.imagesLoaded(function() {
            // Destroy the old handler
            if ($handler.wookmarkInstance) {
                $handler.wookmarkInstance.clear();
            }

            // Create a new layout handler.
            $handler = $('li', $tiles);
            $handler.wookmark(options);
        });
    }
    /**
     * When scrolled all the way to the bottom, add more tiles
     */
    function onScroll() {
        // Check if we're within 100 pixels of the bottom edge of the broser window.
        var winHeight = window.innerHeight ? window.innerHeight : $window.height(), // iphone fix
            closeToBottom = ($window.scrollTop() + winHeight > $document.height() - 100);

        if (closeToBottom) {
            // Get the first then items from the grid, clone them, and add them to the bottom of the grid
            var $items = $('li', $tiles),
                $firstTen = $items.slice(0, 10);
            $tiles.append($firstTen.clone());

            applyLayout();
        }
    };

    // Call the layout function for the first time
    applyLayout();

    // Capture scroll event.
    $window.bind('scroll.wookmark', onScroll);
})(jQuery);