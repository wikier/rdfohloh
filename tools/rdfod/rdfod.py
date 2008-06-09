#!/usr/bin/python
# -*- coding: utf8 -*-
#
# RDFohloh <http://rdfohloh.googlecode.com/>
# RDFod, RDFohloh dump tool
#
# Copyright (C) 2008 Sergio Fernández
#
# This file is part of RDFohloh, a RDF wrapper of Ohloh.
#
# RDFohloh is free software: you can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation, either version 3 of the License, or
# (at your option) any later version.
#
# Foobar is distributed in the hope that it will be useful,
# but WITHOUT ANY WARRANTY; without even the implied warranty of
# MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
# GNU General Public License for more details.
#
# You should have received a copy of the GNU General Public License
# along with Foobar.  If not, see <http://www.gnu.org/licenses/>.

import sys
import os
import urllib2
import socket


class RDFod:

    def __init__(self, users, projects, directory):
        self.nusers = users
        self.nprojects = projects
        self.directory = directory
        self.uri_template = "http://rdfohloh.wikier.org/%s/%i/rdf"
        self.prepare()
        self.retrieve()

    def prepare(self):
        if not (os.path.exists(self.directory)):
            os.mkdir(self.directory)
        if not (os.path.exists(self.directory + "/users")):
            os.mkdir(self.directory + "/users")
        if not (os.path.exists(self.directory + "/projects")):
            os.mkdir(self.directory + "/projects")

    def retrieve(self):
        types = ["user", "project"]
        for t in types:
            n = self.__dict__["n"+t+"s"]
            for i in range(1, n+1):
                uri = self.uri_template % (t, i)
                data = self.get(uri)
                if (data != None):
                    path = self.directory + "/" + t + "s/" + str(i) + ".rdf"
                    if (self.save(path, data)):
                        print "Successfully retrieved %s #%i" % (t, i)
                    else:
                        print "Failed saving %s #%i" % (t, i)
                else:
                    print "Failed retrieving %s #%i" % (t, i)

    def get(self, uri):
        try:
            return HTTPClient.GET(uri)
        except Exception, details:
            return None

    def save(self, path, data):
        try:    
            file = open(path, "w+")
            file.write(data)
            file.flush()
            file.close()
            return True
        except IOError:
            return False


class HTTPClient:
    
    @staticmethod
    def GET(uri):
        headers = { 'User-Agent' : "RDFod (<http://rdfohloh.googlecode.com/; sergio@wikier.org)" }
        request = urllib2.Request(uri)
        response = urllib2.urlopen(request)
        return response.read()


def usage():

    print """
RDFod usage:

    $ python rdfod.py [nusers] [nprojects] [directory]

"""
    sys.exit()

if __name__ == "__main__":

    #default values (retrieved on June 9, 2008)
    users = 135270
    projects = 13711
    directory = "rdfohloh_dump"
    socket.setdefaulttimeout(10)

    try:
        args = sys.argv[1:]
        if (len(args)>0):
            if (args[0]=="--help" or args[0]=="-h"):
                usage()
            else:
                try:
                    users = int(args[0])
                except ValueError:
                    pass
                if (len(args)>1):
                    try:
                        projects = int(args[1])                    
                    except ValueError:
                        pass
                if (len(args)>2):
                    directory = args[2]
                RDFod(users, projects, directory)
    except KeyboardInterrupt:
        print 'Received Ctrl+C or another break signal. Exiting...'

