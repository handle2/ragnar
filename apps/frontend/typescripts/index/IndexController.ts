/// <reference path="./../../../../typings/tsd.d.ts" />
module frontApp {
    interface IIndexController{
        play();
        stop();
    }

    class IndexController implements IIndexController{

        public error = "";

        public waiting = false;

        private interval;

        constructor(private root , private scope, private window ,public translate,public http) {

        }

        public play(){
            var self = this;
            this.waiting = true;

            self.http.post('/play/setQueue').then(function () {
                self.interval = setInterval(function () {
                    self.http.post('/play/getQueue').then(function (data) {
                        if(data.data == "OK"){
                            self.stop();
                            self.window.open('/thegame', '_self');
                        }
                        console.log('still nothing');
                    });
                },1000);
            });


        }

        public stop(){
            this.waiting = false;

            this.http.post('/play/stopQueue');
            clearInterval(this.interval);
        }

    }

    var frontApp = angular.module('frontApp');
    frontApp.controller('IndexController', ['$rootScope','$scope','$window','$translate','$http', IndexController]);
}