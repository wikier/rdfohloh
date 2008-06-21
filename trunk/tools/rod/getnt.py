#!/usr/bin/python
# -*- coding: utf8 -*-
#
# RDFohloh <http://rdfohloh.googlecode.com/>
# GetNT, a simple script to get triples from a RDF/XML file
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
from rdflib.Graph import ConjunctiveGraph


class GetNT:

    def __init__(self, path):
        self.path = path
        self.graph = self.parse()
        print self.get_triples()

    def parse(self):
        g = ConjunctiveGraph()
        g.parse(self.path)
        return g
	
    def get_triples(self):	
        return self.graph.serialize(format="nt")


def usage():

    print """
GetNT usage:

    $ python getnt.py file

"""
    sys.exit()

if __name__ == "__main__":

    try:
        args = sys.argv[1:]
        if (len(args)>0 and os.path.exists(args[0])):
            path = args[0]
            GetNT(path)
        else:
            usage()
    except KeyboardInterrupt:
        print "Received Ctrl+C or another break signal. Exiting..."

