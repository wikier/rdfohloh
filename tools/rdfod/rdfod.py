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
# RDFohloh is distributed in the hope that it will be useful,
# but WITHOUT ANY WARRANTY; without even the implied warranty of
# MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
# GNU General Public License for more details.
#
# You should have received a copy of the GNU General Public License
# along with RDFohloh. If not, see <http://www.gnu.org/licenses/>.

import sys
import os
import urllib2
import time
import socket
import logging

#default values (retrieved on June 12, 2008)
nusers = 135899
nprojects = 13774
sleep = 1
timeout = 10

class RDFod:

    def __init__(self, users, projects, directory="rdfohloh_dump"):
        if (len(users.split("-"))>1):
            try:
                self.start_users = int(users.split("-")[0])
            except ValueError:
                self.start_users = 1
            try:
                self.end_users = int(users.split("-")[1])
            except ValueError:
                self.start_users = nusers
        else:
            self.start_users = 1
            try:
                self.end_users = int(users)
            except ValueError:
                self.start_users = nusers
        
        if (len(projects.split("-"))>1):
            try:
                self.start_projects = int(projects.split("-")[0])
            except ValueError:
                self.start_projects = 1
            try:
                self.end_projects = int(projects.split("-")[1])
            except ValueError:
                self.end_projects = nprojects
        else:
            self.start_projects = 1
            try:
                self.end_projects = int(projects)
            except ValueError:
                self.end_projects = nprojects
        
        self.directory = directory
        self.uri_template = "http://rdfohloh.wikier.org/%s/%i/rdf"
        self.prepare()
        self.logger.info("Starting RDFod")
        self.retrieve()

    def prepare(self):
        #prepare directories
        if not (os.path.exists(self.directory)):
            os.mkdir(self.directory)
        if not (os.path.exists(self.directory + "/users")):
            os.mkdir(self.directory + "/users")
        if not (os.path.exists(self.directory + "/projects")):
            os.mkdir(self.directory + "/projects")

        #configure logger
        self.logger = logging.getLogger("rdfod")
        self.logger.setLevel(logging.DEBUG)
        hdlr = logging.FileHandler("rdfod.log")
        hdlr.setFormatter(logging.Formatter("%(asctime)s %(name)s %(levelname)s: %(message)s"))
        self.logger.addHandler(hdlr)

    def retrieve(self):
        types = ["user", "project"]
        for t in types:
            start = self.__dict__["start_"+t+"s"]
            end = self.__dict__["end_"+t+"s"]
            if (start>0 and start<=end):
                print
                self.logger.info("Starting crawling process for %ss with IDs between %i and %i" %(t, start, end))
                print
                for i in range(start, end+1):
                    uri = self.uri_template % (t, i)
                    data = self.get(uri)
                    if (data != None):
                        path = self.directory + "/" + t + "s/" + str(i) + ".rdf"
                        if (self.save(path, data)):
                            self.logger.info("Successfully retrieved %s #%i" % (t, i))
                        else:
                            self.logger.error("Failed saving %s #%i" % (t, i))
                    else:
                        self.logger.error("Failed retrieving %s #%i" % (t, i))
                    time.sleep(sleep)

    def get(self, uri):
        try:
            return HTTPClient.GET(uri)
        except Exception, details:
            self.logger.error("Error requesting %s: %s" %(uri, details))
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
        headers = {
                    "User-Agent" : "RDFod (<http://rdfohloh.googlecode.com/; sergio@wikier.org)",
                    "Accept"     : "application/rdf+xml"
                  }
        request = urllib2.Request(uri, headers=headers)
        response = urllib2.urlopen(request)
        return response.read()


def usage():

    print """
RDFod usage:

    $ python rdfod.py [nusers] [nprojects]

Numbers could be simple values (starting in 1) or ranges.

Some examples:

    $ python rdfod.py 500 500

    $ python rdfod.py 501-1000 1-500

"""
    sys.exit()

if __name__ == "__main__":

    socket.setdefaulttimeout(10)
    users = str(nusers)
    projects = str(nprojects)
    try:
        args = sys.argv[1:]
        if (len(args)>0):
            if (args[0]=="--help" or args[0]=="-h"):
                usage()
            else:
                users = args[0]
                if (len(args)>1):
                    projects = args[1]
        RDFod(users, projects)
    except KeyboardInterrupt:
        print "Received Ctrl+C or another break signal. Exiting..."

