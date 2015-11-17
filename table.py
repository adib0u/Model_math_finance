from math import exp
from math import log

def apendice(periode, montant, taux):
    rm = exp( log( 1 + (taux/2) ) / 6 ) - 1;
    res = rm * montant * ( ( (1+rm)** (12 * periode) ) / ( (1+rm) ** (12 * periode ) - 1 ))
    return res



def table(taux):
    listePeriode = []
    for k in range(1, 25):
        listePeriode.append(k)
    
    listeMontant = []
    for k in range(1, 10 ):
        listeMontant.append(k * 1000)

    tableApendice = []
    
    for m in range(0, 9):
        for p in range(0, 24):
            tableApendice.append( apendice(listePeriode[p] , listeMontant[m] , taux) )
    return tableApendice
    
print( table(0.08) )