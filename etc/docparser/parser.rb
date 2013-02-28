#!/usr/bin/ruby
# -*- encoding: utf-8 -*-

require 'rubygems'
require 'nokogiri'
require 'active_support/inflector'

f = File.open(File.dirname(__FILE__)+'/api_v1.1_20130227.html')
doc = Nokogiri::HTML(f)
f.close

get_methods = []
post_methods = []

doc.css('.views-field-title > a').each do |link|
  m = link.content.scan(/(GET|POST) ([\w\/:_]+)/)
  api_name = m[0][1]
  method_name = api_name.gsub(/\//, '_').gsub(':', '')

  if /^oauth/ =~ api_name
    next
  end

  if /^(statuses\/filter|statuses\/sample|statuses\/firehose|user|site)$/ =~ api_name
    method_name = ('streaming_' + method_name).camelize(:lower)
    puts <<"EOS"
    public function #{method_name}($params = array(), $callback) { return $this->streaming('#{m[0][1]}', $params, $callback); }
EOS
  else
    http = m[0][0].downcase
    method_name = method_name.camelize(:lower)
    puts <<"EOS"
    public function #{method_name}($params = array()) { return $this->#{http}('#{m[0][1]}', $params); }
EOS
  end
  if m[0][0] == 'GET'
    get_methods.push(m[0][1])
  else
    post_methods.push(m[0][1])
  end
end

#puts "'" + get_methods.join("', '") + "'"
puts "'" + post_methods.join("', '") + "'"
