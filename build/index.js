!function(){"use strict";var e,t={277:function(e,t,n){var r=window.wp.i18n,o=window.wp.blocks,l=window.wp.element,u=window.wp.blockEditor,i=window.wp.serverSideRender,s=n.n(i),a=window.wp.components;(0,o.registerBlockType)("lastcommentedposts/list",{edit:function(e){let{attributes:t,setAttributes:n}=e;const o=(0,u.useBlockProps)();return(0,l.createElement)("div",o,(0,l.createElement)(u.InspectorControls,null,(0,l.createElement)(a.Panel,null,(0,l.createElement)(a.PanelBody,null,(0,l.createElement)(a.PanelRow,null,(0,l.createElement)(a.RangeControl,{label:(0,r.__)("Number of posts","lastcommentedposts"),value:t.max_level,onChange:e=>n({max_level:Number(e)}),min:1,max:10}))))),(0,l.createElement)(s(),{block:"lastcommentedposts/list",attributes:t}))},save:function(e){let{attributes:t}=e;return null}})}},n={};function r(e){var o=n[e];if(void 0!==o)return o.exports;var l=n[e]={exports:{}};return t[e](l,l.exports,r),l.exports}r.m=t,e=[],r.O=function(t,n,o,l){if(!n){var u=1/0;for(c=0;c<e.length;c++){n=e[c][0],o=e[c][1],l=e[c][2];for(var i=!0,s=0;s<n.length;s++)(!1&l||u>=l)&&Object.keys(r.O).every((function(e){return r.O[e](n[s])}))?n.splice(s--,1):(i=!1,l<u&&(u=l));if(i){e.splice(c--,1);var a=o();void 0!==a&&(t=a)}}return t}l=l||0;for(var c=e.length;c>0&&e[c-1][2]>l;c--)e[c]=e[c-1];e[c]=[n,o,l]},r.n=function(e){var t=e&&e.__esModule?function(){return e.default}:function(){return e};return r.d(t,{a:t}),t},r.d=function(e,t){for(var n in t)r.o(t,n)&&!r.o(e,n)&&Object.defineProperty(e,n,{enumerable:!0,get:t[n]})},r.o=function(e,t){return Object.prototype.hasOwnProperty.call(e,t)},function(){var e={826:0,46:0};r.O.j=function(t){return 0===e[t]};var t=function(t,n){var o,l,u=n[0],i=n[1],s=n[2],a=0;if(u.some((function(t){return 0!==e[t]}))){for(o in i)r.o(i,o)&&(r.m[o]=i[o]);if(s)var c=s(r)}for(t&&t(n);a<u.length;a++)l=u[a],r.o(e,l)&&e[l]&&e[l][0](),e[l]=0;return r.O(c)},n=self.webpackChunklastcommentposts=self.webpackChunklastcommentposts||[];n.forEach(t.bind(null,0)),n.push=t.bind(null,n.push.bind(n))}();var o=r.O(void 0,[46],(function(){return r(277)}));o=r.O(o)}();