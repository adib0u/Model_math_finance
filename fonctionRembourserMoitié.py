

def demi(N , ra , p0):
    r = ra/12
    print(r)
    mens = r * p0 *( (1+r) ** (12*N) ) / (( (1+r) ** (12 * N) ) - 1 )
    
    for k in range(0,12*N+1):
        pk = p0*(1+r)**k - mens * (((1+r) ** k )- 1)/r
        print (k,round(pk,3))
        if(pk <= (p0/2)):
            print ("la moitié est remboursé")
            
def demiEffectif(N , ra , p0):
    r = ((1+ra/12)**12-1)/12
    print(r)
    mens = r * p0 *( (1+r) ** (12*N) ) / (( (1+r) ** (12 * N) ) - 1 )
    
    for k in range(0,12*N+1):
        pk = p0*(1+r)**k - mens * (((1+r) ** k )- 1)/r
        print (k,round(pk,3))
        if(pk <= (p0/2)):
            print ("la moitié est remboursé")
        
