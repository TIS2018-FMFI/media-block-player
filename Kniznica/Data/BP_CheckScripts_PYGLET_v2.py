#v2:
#IN: Script with |,||,|||
#    Translation with |,||,|||
#OUT: Block counts in level 1,2,3

# INPUT ##################################################
ORIGfile = "eh09.txt"
TRANfile = "eh09_EN.txt"
# END OF INPUT ############################################

try:
  # read original script with | splitters
  file = open(ORIGfile,'r',encoding='utf-8')
  text_ORIG = str(file.read())
  file.close()
except:
  print('Warning: Script ' + ORIGfile +' canot be opened or read')
  script = False

if "|" not in text_ORIG:
  print("ERROR: No splitter "|" found in ORIG script!")
  exit()

if "|||" in text_ORIG: ORIGlevel = 3
elif "||" in text_ORIG: ORIGlevel = 2
else: ORIGlevel = 1

try:
  # read paralel translation
  file = open(TRANfile,'r',encoding='utf-8')
  text_TRAN = str(file.read())
  file.close()
except:
  print('ERROR: ' + TRANfile+' canot be opened or read')
  transl = False
  text_TRAN = ''
if "|" not in text_TRAN:
  print("ERROR: No splitter | found in TRANslation!")
  exit()
################################################################
####################### split script into list
blocks_ORIG1 = text_ORIG.split("|")
#print("Orig.script raw blocs=",len(blocks_ORIG1))   DEBUG
empty_count = blocks_ORIG1.count('')
for k in range(empty_count):
  blocks_ORIG1.remove('')    # remove empty strings due to || and ||| splitters
print("Orig.script L1 blocs=",len(blocks_ORIG1))

if "||" in text_ORIG:
  blocks_ORIG2 = text_ORIG.split("||")
  #remove items with leading | due to ||| splitters
  index = 0
  for block in blocks_ORIG2:
    if block[0] == "|":
      blocks_ORIG2[index] = block[1:] # remove leading |
    index = index + 1
  print("Orig.script L2 blocs=",len(blocks_ORIG2))

if "|||" in text_ORIG:
  blocks_ORIG3 = text_ORIG.split("|||")
  print("Orig.script L3 blocs=",len(blocks_ORIG3))
    
########################### split paralel translation into list
blocks_TRAN1 = text_TRAN.split("|")
#print("Transl.script raw blocs=",len(blocks_TRAN1))  DEBUG
empty_count = blocks_TRAN1.count('')
for k in range(empty_count):
  blocks_TRAN1.remove('')    # remove empty strings due to || and ||| splitters
count1 = len(blocks_TRAN1)
print("Transl.script L1 blocs=",len(blocks_TRAN1))

if "||" in text_TRAN:
  blocks_TRAN2 = text_TRAN.split("||")
  #remove items with leading | due to ||| splitters
  index = 0
  for block in blocks_TRAN2:
    if block[0] == "|":
      blocks_TRAN2[index] = block[1:] # remove leading |
    index = index + 1
  count2 = len(blocks_TRAN2) 
  print("Transl.script L2 blocs=",len(blocks_TRAN2))

if "|||" in text_TRAN:
  blocks_TRAN3 = text_TRAN.split("|||")
  count3 = len(blocks_TRAN3)
  print("Transl.script L3 blocs=",len(blocks_TRAN3))
######################################################
frmt = '{:<' + str(40) + '}'  # format 1st column

level = input(" Print all blocks in level (ORIG,0,1,2,3):")
if level == "": exit()
if level == '0':  # all splits incl. empty (to discover dif. in ORIG vs. TRAN)
  blocks_ORIG = text_ORIG.split("|")
  blocks_TRAN  = text_TRAN.split('|')
  lenorig = len(blocks_ORIG)
  lentran = len(blocks_TRAN)
  if lenorig < lentran:
    min = lenorig
  else:
    min = lentran
  for i in range(min):
    print(i,blocks_ORIG[i].replace("\n"," "))
    print('  ',blocks_TRAN[i].replace("\n"," "))    
elif level == "1":
  for i in range(count1):
    print(i,blocks_ORIG1[i].replace("\n"," "))
    print('  ',blocks_TRAN1[i].replace("\n"," "))
elif level == "2":
  for i in range(count2):
    print(i,blocks_ORIG2[i].replace("\n"," "))
    print('  ',blocks_TRAN2[i].replace("\n"," "))
elif level == "3":
  for i in range(count3):
    print(i,blocks_ORIG3[i].replace("\n"," "))
    print('  ',blocks_TRAN3[i].replace("\n"," "))
elif level.upper() == "ORIG":
  for i in range(count1):
    print(i,blocks_ORIG1[i].replace("\n"," ")) 


