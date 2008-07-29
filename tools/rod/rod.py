#!/usr/bin/python
# -*- coding: utf8 -*-
#
# RDFohloh <http://rdfohloh.googlecode.com/>
# ROD, RDFohloh dump tool
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
from rdflib.Graph import ConjunctiveGraph
from rdflib.sparql.bison import Parse
from cStringIO import StringIO

#default values (retrieved on June 21, 2008)
nusers = 138228
nprojects = 13960

#configuration
sleep = 1
fail = 30
timeout = 10
attempts = 5

class ROD:

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
        self.logger.info("Starting ROD")
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
        self.logger = logging.getLogger("rod")
        self.logger.setLevel(logging.DEBUG)
        hdlr = logging.FileHandler("rod.log")
        hdlr.setFormatter(logging.Formatter("%(asctime)s %(name)s %(levelname)s: %(message)s"))
        self.logger.addHandler(hdlr)

    def retrieve(self):
        types = ["user", "project"]
        for t in types:
            start = self.__dict__["start_"+t+"s"]
            end = self.__dict__["end_"+t+"s"]
            if (start>0 and start<=end):
                self.logger.info("Starting crawling process for %ss with IDs between %i and %i" %(t, start, end))
                for i in range(start, end+1):
                    a = attempts
                    uri = self.uri_template % (t, i)
                    data = self.get(uri)
                    
                    while (data==None or not self.isRDF(data, t, i)):
                        a = a - 1
                        if (a==0):
                            break;
                        time.sleep(fail)
                        self.logger.debug("Failed attempt #%i to get %s #%i" % (attempts-a, t, i))
                        data = self.get(uri)
                    
                    if (a > 0):
                        path = self.directory + "/" + t + "s/" + str(i) + ".rdf"
                        if (self.save(path, data)):
                            self.logger.info("Successfully retrieved %s #%i" % (t, i))
                        else:
                            self.logger.error("Failed saving %s #%i" % (t, i))
                    else:
                        self.logger.error("It was impossible to get %s #%i" % (t, i))
                    
                    time.sleep(sleep)
                    
                self.logger.info("Finished crawling process for %ss" % t)

    def get(self, uri):
        try:
            return HTTPClient.GET(uri)
        except Exception, details:
            self.logger.debug("Error requesting %s: %s" %(uri, details))
            return None

    def isRDF(self, data, type, id):
        g = ConjunctiveGraph()
        try:
            g.load(StringIO(data))
            query = ""
            if (type == "user"):
                query = """
                            SELECT ?email 
                            WHERE { 
                              ?user <http://www.w3.org/1999/02/22-rdf-syntax-ns#type> <http://rdfs.org/sioc/ns#User> . 
                              ?user <http://rdfs.org/sioc/ns#email_sha1> ?email 
                            }
                        """
            if (type == "project"):
                query = """
                        """
            if (len(g.query(Parse(query)).serialize("python")[0])>0):
                return True
            else:
                return False
        except Exception, e:
            print str(e)
            self.logger.debug("Error parsing RDF of %s %i" %(type, id))
            return False

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
                    "User-Agent" : "ROD (<http://rdfohloh.googlecode.com/; sergio@wikier.org)",
                    "Accept"     : "application/rdf+xml"
                  }
        request = urllib2.Request(uri, headers=headers)
        response = urllib2.urlopen(request)
        return response.read()


def usage():

    print """
ROD usage:

    $ python rod.py [nusers] [nprojects]

Numbers could be simple values (so will start in 1) or ranges.

Some examples:

    $ python rod.py 500 500

    $ python rod.py 501-1000 1-500

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
        ROD(users, projects)
    except KeyboardInterrupt:
        print "Received Ctrl+C or another break signal. Exiting..."

