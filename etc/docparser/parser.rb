#!/usr/bin/ruby
# -*- encoding: utf-8 -*-

require 'rubygems'
require 'nokogiri'
require 'open-uri'
require 'erb'
require 'active_support/inflector'

api_doc = File.open(File.dirname(__FILE__)+'/api_v1.1_20130227.html') { |f| Nokogiri::HTML(f) }

rest_api_doc_template = ERB.new(<<"EOS"
/**
 * @param array $params
 * @return array
<% api[:url].each do |url| %> * @see <%= url %>
<% end %> */
EOS
)
rest_api_template = ERB.new(<<"EOS"
public function <%= method_name %>($params = array())
{
  return $this-><%= api[:http_method][0] %>('<%= api[:name] %>', $params);
}

EOS
)
rest_rw_api_template = ERB.new(<<"EOS"
public function <%= method_name %>($params = array())
{
    if (empty($params)) {
        return $this->get('<%= api[:name] %>', $params);
    } else {
        return $this->post('<%= api[:name] %>', $params);
    }
}

EOS
)
streaming_api_template = ERB.new(<<"EOS"
/**
 * @param array $params
 * @param $callback
 * @return array
<% api[:url].each do |url| %> * @see <%= url %><% end %>
 */
public function <%= method_name %>($params = array(), $callback)
{
  return $this->streaming('<%= api[:name] %>', $params, $callback);
}

EOS
)

api_items = {}
base_url = api_doc.css('meta[@name = "twitter:url"]').attr('content')

api_doc.css('.views-field-title > a').each do |link|
  m = link.content.scan(/(GET|POST) ([\w\/:_]+)/)
  api_name = m[0][1]
  next if /^oauth/ =~ api_name

  http_method = m[0][0].downcase
  method_name = api_name.gsub(/\//, '_').gsub(':', '')
  api_url = URI.join(base_url, link.attr('href'))

  if api_items.has_key?(method_name)
    api_items[method_name][:http_method].push(http_method)
    api_items[method_name][:url].push(api_url)
  else
    api_items[method_name] = {
        http_method: [http_method],
        name: api_name,
        url: [api_url]
    }
  end
end

post_api_names = []
api_items.each do |method_name, api|
  if /^(?:statuses\/filter|statuses\/sample|statuses\/firehose|user|site)$/ =~ api[:name]
    method_name = ("streaming_#{method_name}").camelize(:lower)
    puts streaming_api_template.result(binding)
  else
    post_api_names.push(api[:name]) if api[:http_method].include?('post')
    method_name = method_name.camelize(:lower)
    puts rest_api_doc_template.result(binding)
    puts (api[:url].size > 1) ? rest_rw_api_template.result(binding) : rest_api_template.result(binding)
  end
end

ERB

template = ERB.new <<EOS
$this->postMethodNames = array(
<% post_api_names.each do |name| %>'<%= name %>',
<% end %>);
EOS
puts template.result(binding)

