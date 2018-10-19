#v3 changes: block_from - block_to subinteval implemented

# INPUT ##################################################
AUDIOfile = 'histoire04_1.wav'
#AUDIOfile = .wav file
# [AUDIOfile].jkr file with L1_markers[], SKIP_markers[]
#                 L1_markers[] =[split1Time1,...,endOfLastBlock <= DURATION]
#                 SKIP_markers[] ...ends of intervals to be skipped,
#                                   must be present also in L1_markers[] and
#                                   cannot be within L2 and L3 blocks
# [AUDIOfile].txt original script with | (Level1),|| (L2),||| (L3) splitters
TRAN = 'SK' # ... paralel translation
# [AUDIOfile]_<TRAN>.txt paralel translation into language <TRAN>
pause = 1 # pause between blocks in sec, 99 = waiting for ENTER
repeat = 2  # howmany times to repeat a block
pause_repeat = 0 # pause between repeatings of blocks
direction = 'F'  # Forward/Backward/Shuffle
level = 1 # block level 1/2/3
script = True   # may be rewritten if script file not existing
transl = True   # may be rewritten if translation file not existing
#print_delay =  in secs. or 'AFTER'  TO BE DONE!!!!
WIDTH = 40
pause_before_play = 0
block_from = 0     # L1 block to start with  (0 is the 1st)
block_to = 'LAST'  # L1 block number to end with (inclusive) or 'LAST'

# Learning activities settings:
first_listening =         False
pronounciation_training = False
pronounciation_check =    False
continual_echoing =       False
understanding_check =     False  # tansl after: TO BE DONE
speaking_check =          False
prompting =               False
writing_check =           False
#############################################
if first_listening:
  pause = 99
  repeat = 1
  direction = 'F'
  script = False
  transl = True
elif pronounciation_training:
  pause = 0
  repeat = 2
  pause_repeat = 2
  script = True
  transl = True
elif pronounciation_check:
  pause = 0
  pause_before_play = 99
  script = True
  transl = True
elif continual_echoing:
  pause = 0
  script = True
  transl = True
  pause_before_play = .5
elif understanding_check:
  pause = 99
  pause_before_play = 0
  script = False
  transl = False
elif speaking_check:
  pause_before_play = 99
  script = True
  transl = True
  repeat = 1
  pause = 0
elif prompting:
  pause = 99
  repeat = 1
  pause_repeat = 0
  direction = 'F'
  script = True
  transl = False
elif writing_check:
 pause_before_play = 0
 pause = 99
 pause_repeat = 0
 repeat = 2
 script = False
 transl = True

# END OF INPUT ############################################

JKRfile = AUDIOfile[0:len(AUDIOfile)-4]+'.jkr'
ORIGfile = AUDIOfile[0:len(AUDIOfile)-4]+'.txt'
TRANfile = AUDIOfile[0:len(AUDIOfile)-4]+'_'+TRAN+'.txt'

import pyglet, time, random

def play_block(start,end):
  player.seek(start)
  player.play()
  if (end != 9999) and (end < DURATION): # 9999 plays AUDIOfile to the end
    time.sleep(end-start)
    player.pause()
######################################################
try:
  source = pyglet.media.load(AUDIOfile,streaming=False)
except:
  print(AUDIOfile+' cannot be loaded')
  exit()
player = pyglet.media.Player()
player.queue(source)
DURATION = round(source.duration,2)

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
if level == 2 and "||" not in text_ORIG:
  print("ERROR: No || spliter found in level 2")
  exit()
if level == 3 and "|||" not in text_ORIG:
  print("ERROR: No ||| spliter found in level 3")
  exit()

if "|||" in text_ORIG: ORIGlevel = 3
elif "||" in text_ORIG: ORIGlevel = 2
else: ORIGlevel = 1


if transl:
  try:
    # read SK paralel translation
    file = open(TRANfile,'r',encoding='utf-8')
    text_TRAN = str(file.read())
    file.close()
  except:
    print('Warning: ' + TRANfile+' canot be opened or read')
    transl = False
  if "|" not in text_TRAN:
    print("Warning: No splitter "|" found in TRANslation!")
    TRANlevel = 0
  elif "|||" in text_TRAN:
    TRANlevel = 3
  elif "||" in text_TRAN:
    TRANlevel = 2
  else:
    TRANlevel = 1

  if ORIGlevel != TRANlevel:
    print("ERROR: level != TRANlevel")
    exit()

try:
  file_jkr =open(JKRfile,'r')
except:
  print("ERROR:",JKRfile + ' does not exist or cannot be opened')
  exit()

for row in file_jkr :
  try:
    exec(row.strip())  # executes command in row
    #print(row.strip())  #DEBUG
  except:
    print('Warning: '+ row.strip()+' is invalid command')
file_jkr.close()

#################################################################
# generate L1_intervals[]; L1_markers, SKIP_markes were read from .jkr file
L1_intervals = []
i1 = 0 # start of inteval
for i2 in L1_markers:   # ends of intervals incl. SKIPped
  if i2 not in SKIP_markers:
    L1_intervals.append([i1,i2])
  i1 = i2  # set start of the next interval
#print("L1:",L1_intervals) #DEBUG
  

  
################################################################
L1_indexes = []  # positions of single | splitters
start = 0
while True:
  i = text_ORIG.find("|",start)
  if i == -1 : break
  L1_indexes.append(i)
  start = i + 3   # to exclude || and |||; Warning: |<single letter>| block is not expected!!!
L1_indexes.append("END")

if block_to == 'LAST':
  block_to = len(L1_indexes) - 1 # to the end od script
if block_from > block_to:
  print('ERROR: block_from >= block_to')
  exit()
if block_to > len(L1_indexes) - 1 :
  block_to = len(L1_indexes) - 1
if block_from < 0:
  block_from = 0

if level == 2:
  L2_indexes = [] # positions of double || splitters
  start = 0
  while True:
    i = text_ORIG.find("||",start)
    if i == -1 : break
    L2_indexes.append(i)
    start = i + 2   # to exclude |||
  L2_indexes.append("END")
  L2_count = len(L2_indexes)
  #print(L2_indexes) #DEBUG
  
  block2_map = []  # mapping L1 -> L2
  j = 0
  for x in L1_indexes:
    block2_map.append(j)
    if x in L2_indexes:
      j = j +1
##########  adjust block_from, block_to to  to comprise the whole L2 blocks
  b = block2_map[block_from]
  block_from = block2_map.index(b)  # set to the start of L2 block
  b = block2_map[block_to]
  if b == block2_map[-1]:  #last L2 block
    block_to = len(block2_map) -1  # set to the last L1 block
  else:
    block_to = block2_map.index(b+1) - 1  #set to the end of L2 block
  

if level == 3:
  L3_indexes = []  # positions of triple ||| splitters
  start = 0
  while True:
    i = text_ORIG.find("|||",start)
    if i == -1 : break
    L3_indexes.append(i)
    start = i + 1
  L3_indexes.append("END")
  L3_count = len(L3_indexes)

  block3_map = []  # mapping L1 -> L3
  j = 0
  for x in L1_indexes:
    block3_map.append(j)
    if x in L3_indexes:
      j = j +1
##########  adjust block_from, block_to to  to comprise the whole L3 blocks
  b = block3_map[block_from]
  block_from = block3_map.index(b)  # set to the start of L3 block
  b = block3_map[block_to]
  if b == block3_map[-1]:  #last L3 block
    block_to = len(block3_map) -1  # set to the last L1 block
  else:
    block_to = block3_map.index(b+1) - 1  #set to the end of L3 block
################################ Compare block count in script and in L1_markers
L1_count = len(L1_indexes)                 # counted from script
L1 = len(L1_markers) - len(SKIP_markers)   # counted from audio markers
if L1 != L1_count:
  print("ERROR: L1 blocks != L1_markers - SKIP_markers")
  print("L1 text blocks=",L1_count)
  print("Audio L1_markers=",len(L1_markers))
  print("Audio SKIP_markers=",len(SKIP_markers))
  exit() 
################### generate intervals[] for level = 1
if level == 1:
  intervals = []
  for i in range(block_from,block_to + 1):
    intervals.append(L1_intervals[i])
################### generate intervals[] of level 2
if level == 2:
  intervals = []
  new = True
  for i in range(block_from,block_to+1):
    if new:
      a1 = L1_intervals[i][0]
      new = False
    if L1_indexes[i] in L2_indexes:
      a2 = L1_intervals[i][1]
      intervals.append([a1,a2])
      new = True
#print(intervals)  
################### generate intervals[] of level 3
if level == 3:
  intervals = []
  new = True
  for i in range(block_from,block_to+1):
    if new:
      a1 = L1_intervals[i][0]
      new = False
    if L1_indexes[i] in L3_indexes:
      a2 = L1_intervals[i][1]
      intervals.append([a1,a2])
      new = True
####################### split script into list
blocks_ORIG1 = text_ORIG.split("|")
empty_count = blocks_ORIG1.count('')
for k in range(empty_count):
  blocks_ORIG1.remove('')    # remove empty strings due to || and ||| splitters
b1_count = len(blocks_ORIG1)
########################### split paralel translation into list
if level == 1:   separator = "|"
elif level == 2: separator = "||"
elif level == 3: separator = "|||"
else:
  print("ERROR: Unknown level")
  exit()

if transl:
  blocks_TRANx = text_TRAN.split(separator)
  #remove empty items
  empty_count = blocks_TRANx.count('')
  for k in range(empty_count):
    blocks_TRANx.remove('')    
  index = 0
  for block in blocks_TRANx:
    if block[0] == "|":
      blocks_TRANx[index] = block[1:] # remove leading |
    index = index + 1
################################
if level == 1:
  blocks_ORIG = []
  blocks_TRAN = []
  for i in range(block_from,block_to+1):
    blocks_ORIG.append(blocks_ORIG1[i])
    if transl: blocks_TRAN.append(blocks_TRANx[i])
  
if level == 2:
  blocks_ORIG2 = text_ORIG.split("||")
  #cut off 1st char in items with leading '|' (due to ||| splitters)
  index = 0
  for block in blocks_ORIG2:
    if block[0] == "|":
      blocks_ORIG2[index] = block[1:] # remove leading '|'
    index = index + 1
  block2_from = block2_map[block_from]
  block2_to =   block2_map[block_to]
  blocks_ORIG = []
  blocks_TRAN = []
  for i in range(block2_from,block2_to+1):
    blocks_ORIG.append(blocks_ORIG2[i])
    if transl: blocks_TRAN.append(blocks_TRANx[i])

#.......................................pokracuj
if level == 3:
  blocks_ORIG3 = text_ORIG.split("|||")
  block3_from = block3_map[block_from]
  block3_to =   block3_map[block_to]
  blocks_ORIG = []
  blocks_TRAN = []
  for i in range(block3_from,block3_to+1):
    blocks_ORIG.append(blocks_ORIG3[i])
    if transl: blocks_TRAN.append(blocks_TRANx[i])
    
# ToBeDone: if SKIP_markers does not exist then SKIP_markers =[]

###################generate sequence of indices for selected direction
block_count = len(blocks_ORIG)
if direction.upper() == 'F': #forward
  sequence = list(range(block_count))  # [0,1,...,block_count-1]
elif direction.upper() == 'B':  # backward
  sequence = list(range(block_count-1,-1,-1)) # [block_count-1,...,2,1,0]
elif direction.upper() == 'S':  # shuffle 
  shuffle_list = list(range(block_count))     # [<random permutation>]
  random.shuffle(shuffle_list)
  sequence = shuffle_list
else:
  print('ERROR: Unknown direction '+ direction)
  exit()

########################################################
# PLAY
frmt = '{:<' + str(WIDTH) + '}'  # format 1st column
next = ''
if pause == 99:
  print("ENTER to continue; 0 to repeat; C to cancel>")
elif pause_before_play == 99:
  print("ENTER to continue; C to cancel>")
for i in sequence:
  t1 = blocks_ORIG[i].strip()
  if transl:
    t2 = blocks_TRAN[i].strip()
  else: t2 = ''
  T1 = t1.split("|")
  T2 = t2.split("|")
  N = len(T1)
  if script and transl and N != len(T2):
    print("ERROR: L1 blocks count in script and translation differ")
    exit()
  for j in range(N):
    if T1[j] == "" and '|' in t1: continue   # skip empty string (due to || in L3)
    b1 = T1[j].replace("\n"," ")
    if transl:
      b2 = T2[j].replace("\n"," ")
    else:
      b2 = ""
    if not script: b1 = ""
    if speaking_check: b1 = ""
    print(frmt.format(b1),b2) #DEBUG i,

  a1 = intervals[i][0]
  a2 = intervals[i][1]
  if pause_before_play != 99:
    time.sleep(pause_before_play)
  else:
    x = input() #"ENTER to continue; C to cancel>")
    if x.upper() == 'C' : exit()
  if speaking_check:
    for j in range(N):
      if T1[j] == "": continue   # skip empty string (due to || in L3)
      b1 = T1[j].replace("\n"," ")
      print(frmt.format(b1))
    
  if next != '+':
    for j in range(repeat):
      play_block(a1,a2)
      #print(a1,a2) #DEBUG
      if j != repeat-1: time.sleep(pause_repeat)# last repeat => no after pause    
  if pause == 99:  # indefinite pause
    while True:
      next = input() #"ENTER to continue; 0 to repeat; +next C to cancel>")
      if next.upper() == 'C': exit()
      if next.upper() == '0':
        for j in range(repeat):
          play_block(a1,a2)
          if j != repeat-1: time.sleep(pause_repeat)
      else: break
    continue
  else :            # definite pause
    time.sleep(pause)
# END OF PLAY ###########################################  
