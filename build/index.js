(()=>{"use strict";var e,t={181:(e,t,o)=>{const n=window.wp.blocks,s=JSON.parse('{"$schema":"https://json.schemastore.org/block.json","apiVersion":2,"name":"lastcommentedposts/list","version":"2.2.0","title":"Last Commented Posts","category":"layout","keywords":["Last Commented","Latest Comments","Recent Comments"],"icon":"editor-ol","description":"Adds a block that lists the recent commented posts without duplicates.","attributes":{"max_level":{"type":"integer","default":5}},"supports":{"align":["wide","full"]},"textdomain":"lastcommentedposts","editorScript":"file:index.js","style":"file:style-index.css","editorStyle":"file:index.css"}'),r=window.wp.element,l=window.wp.i18n,a=window.wp.blockEditor,i=window.wp.serverSideRender;var c=o.n(i);const m=window.wp.components;(0,n.registerBlockType)(s,{edit:function(e){let{attributes:t,setAttributes:o}=e;const n=(0,a.useBlockProps)();return(0,r.createElement)("div",n,(0,r.createElement)(a.InspectorControls,null,(0,r.createElement)(m.Panel,null,(0,r.createElement)(m.PanelBody,null,(0,r.createElement)(m.PanelRow,null,(0,r.createElement)(m.RangeControl,{label:(0,l.__)("Number of posts","lastcommentedposts"),value:t.max_level,onChange:e=>o({max_level:Number(e)}),min:1,max:10}))))),(0,r.createElement)(c(),{block:"lastcommentedposts/list",attributes:t}))},save:function(e){let{attributes:t}=e;return null}})}},o={};function n(e){var s=o[e];if(void 0!==s)return s.exports;var r=o[e]={exports:{}};return t[e](r,r.exports,n),r.exports}n.m=t,e=[],n.O=(t,o,s,r)=>{if(!o){var l=1/0;for(m=0;m<e.length;m++){for(var[o,s,r]=e[m],a=!0,i=0;i<o.length;i++)(!1&r||l>=r)&&Object.keys(n.O).every((e=>n.O[e](o[i])))?o.splice(i--,1):(a=!1,r<l&&(l=r));if(a){e.splice(m--,1);var c=s();void 0!==c&&(t=c)}}return t}r=r||0;for(var m=e.length;m>0&&e[m-1][2]>r;m--)e[m]=e[m-1];e[m]=[o,s,r]},n.n=e=>{var t=e&&e.__esModule?()=>e.default:()=>e;return n.d(t,{a:t}),t},n.d=(e,t)=>{for(var o in t)n.o(t,o)&&!n.o(e,o)&&Object.defineProperty(e,o,{enumerable:!0,get:t[o]})},n.o=(e,t)=>Object.prototype.hasOwnProperty.call(e,t),(()=>{var e={826:0,431:0};n.O.j=t=>0===e[t];var t=(t,o)=>{var s,r,[l,a,i]=o,c=0;if(l.some((t=>0!==e[t]))){for(s in a)n.o(a,s)&&(n.m[s]=a[s]);if(i)var m=i(n)}for(t&&t(o);c<l.length;c++)r=l[c],n.o(e,r)&&e[r]&&e[r][0](),e[r]=0;return n.O(m)},o=globalThis.webpackChunklastcommentposts=globalThis.webpackChunklastcommentposts||[];o.forEach(t.bind(null,0)),o.push=t.bind(null,o.push.bind(o))})();var s=n.O(void 0,[431],(()=>n(181)));s=n.O(s)})();