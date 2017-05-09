/**
 * Created by Krisz on 2017.05.01..
 */
/// <reference path="./../../../../typings/tsd.d.ts" />
module frontApp {
    interface ICommonController{
        changeLang(langKey):void;
        hasPermission(code):boolean;
    }

    class CommonController implements ICommonController{


        public contents = [];

        constructor(private root , private scope, private window, private location, public commonService,public translate, private localStorageService) {

            if(!localStorageService.get('lang')){
                localStorageService.set('lang','hu');
                translate.use(localStorageService.get('lang'));
            }else{
                translate.use(localStorageService.get('lang'));
            }

            this.setLangSession(localStorageService.get('lang'));
            root.language = localStorageService.get('lang');

        }

        public hasPermission(code){
            return this.commonService.hasPermission(code);
        }

        public changeLang(langKey){

            if(this.localStorageService.get('lang')!=langKey){
                this.localStorageService.set('lang',langKey);
                this.translate.use(langKey);
                this.setLangSession(langKey);
                this.root.language = this.localStorageService.get('lang');
            }

            var currentUrl =  this.location.path();
            this.window.open(currentUrl, '_self');
        }

        private setLangSession(code){
            //this.commonService.http.post('/admin/language/setLang',angular.toJson(code));
        }

        public getContents(labels:Array<string>){
            var self = this;
            /*this.commonService.http.post('/content/list',angular.toJson(labels)).then(function (response) {
                self.contents = response.data;
            });*/
        }

    }

    var frontApp = angular.module('frontApp');
    frontApp.controller('CommonController', ['$rootScope','$scope','$window','$location', 'CommonService','$translate', 'localStorageService', CommonController]);
}