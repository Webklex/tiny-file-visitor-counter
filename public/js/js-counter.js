/*
 * File: js-counter.js
 * Category: -
 * Author: MSG - Webklex
 * URL: http://webklex.com
 * Created: 19.01.16 19:24
 * Updated: -
 *
 * Description:
 *  -
 */

(function(){
    jsCounter = {

        /*jsCounter options*/
        options: {
            scope: document,
            tagPrefix: 'js-counter-',
            backend: 'backend.php',
            async: true,
            live: {
                enabled: true,
                timeout: 5000,
                resource: null
            }
        },

        /*Private settings witch shouldn't be changed*/
        private: {
            request: {},
            backend: {
                counter: []
            },
            loading: false
        },

        /* Init the jsCounter
         * @param object options
         *
         * @return self
         * */
        init: function(options){
            this.setOptions(options);
            this.initPrivates();
            this.loadStatistics();
            this.interval();

            return this;
        },

        interval: function(){
            if(this.options.live.enabled){
                var that = this;
                that.options.live.resource = setInterval(function(){
                    that.loadStatistics();
                }, this.options.live.timeout);
            }
        },

        /* Init all private vars
         * @return self
         * */
        initPrivates: function(){
            return this;
        },

        /* Set options object
         * @param object options
         *
         * @return self
         * */
        setOptions: function(options){
            for(var key in options){
                // skip loop if the property is from prototype
                if (!options.hasOwnProperty(key)) continue;
                if(this.options.hasOwnProperty(key)){
                    this.options[key] = options[key];
                }
            }
            return this;
        },

        /* Load all required statistics from the server
         * @return void
         * */
        loadStatistics: function(){
            var xmlhttp = new XMLHttpRequest();

            var that = this;
            xmlhttp.onreadystatechange = function() {
                if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                    that.backend(JSON.parse(xmlhttp.responseText));
                }
            };
            xmlhttp.open("GET", this.options.backend, this.options.async);
            xmlhttp.send();
        },

        /* Async callback - required to continue
         * @param object backend
         *
         * @return void
         * */
        backend: function(backend){
            if(backend.success == true){
                this.options.backend = backend.backend;
                this.private.backend = backend;
                this.render();
            }
        },

        /* Render the output - fill tags
         * @return void
         * */
        render: function(){
            for(var key in this.private.backend){
                // skip loop if the property is from prototype
                if (!this.private.backend.hasOwnProperty(key)) continue;

                for(var i = 0; i < this.options.scope.getElementsByTagName(this.options.tagPrefix+key.toLowerCase()).length; i++){
                    this.options.scope.getElementsByTagName(this.options.tagPrefix+key.toLowerCase())[i].innerHTML = this.private.backend[key];
                }

            }
        }
    };
})();