{**  
 * Fat-Free Framework
 * View and Template
 * 做法請看 http://fatfreeframework.com/views-and-templates#conditional-segments
 *}
<check if="{{ @callback=='' }}">
    <true>
        {{ @json | raw }}
    </true>
    <false>
        {{ @callback }}({{ @json | raw }});
    </false>
</check>
    