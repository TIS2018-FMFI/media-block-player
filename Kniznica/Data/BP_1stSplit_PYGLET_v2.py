# INPUT
AUDIOfile = 'eh09.wav'
# [AUDIOfile].txt original script with | splitters
TRAN = 'SK' # Slovak paralel translation
# [AUDIOfile]_<TRAN>.txt paralel translation into language <TRAN>
reaction_time = .1
script = True   # may be rewritten if script file not existing or if reverse_translation
transl = True   # may be rewritten if translation file not existing
WIDTH = 40
level = 1
separator = '|'

# OUTPUT
# <AUDIOfile>.jkr (replace .jkr if existing !)
#                 L1_markers =[split1Time1,...,endOfLastBlock <= DURATION]
#############################################

JKRfile = AUDIOfile[0:len(AUDIOfile)-4]+'.jkr'
ORIGfile = AUDIOfile[0:len(AUDIOfile)-4]+'.txt'
TRANfile = AUDIOfile[0:len(AUDIOfile)-4]+'_'+TRAN+'.txt'

import pyglet, time
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
  text_ORIG = file.read()
  file.close()
except:
  print('Warning: ' + ORIGfile +' canot be opened or read')
  script = False

try:
  # read SK paralel translation
  file = open(TRANfile,'r',encoding='utf-8')
  text_TRAN = file.read()
  file.close()
except:
  print('Warning: ' + TRANfile+' canot be opened or read')
  transl = False


# split script and paralel translation into lists
blocks_ORIG = text_ORIG.split(separator)
#remove empty items
empty_count = blocks_ORIG.count('')
for k in range(empty_count):
  blocks_ORIG.remove('')
block_count = len(blocks_ORIG)

if transl:
  blocks_TRAN = text_TRAN.split(separator)
  #remove empty items
  empty_count = blocks_TRAN.count('')
  for k in range(empty_count):
    blocks_TRAN.remove('')    
  if block_count != len(blocks_TRAN):
    print('Block count in translation != in script!')
    exit()

def play_block(start,end):
  player.seek(start)
  player.play()
  if (end != 9999) and (end < DURATION): # 9999 plays AUDIOfile to the end
    time.sleep(end-start)
    player.pause()
######################################################
L1_markers = []    # Level 1 time markers
SKIP_markers = []  # ends of skipped intervals
t1 = 0.0             # relative time - start of interval <t1;t2>
i = 0 # block number
print('[Enter] to Pause')
while i < len(blocks_ORIG):
  if script:
    txt1 = blocks_ORIG[i].strip()
  else: txt1 = ''
  if transl:
    txt2 = blocks_TRAN[i].strip()
  else: txt2 = ''
  frmt = '{:<' + str(WIDTH) + '}'
  print(frmt.format(txt1),txt2)  
  play_block(t1,9999)
  clock1 = time.clock()   # absolute time
  input()   # waiting for ENTER
  clock2 = time.clock()   # absolute time when ENTER pressed
  t2 = round(t1 + clock2 - clock1 - reaction_time,2)    # relative time - end of interval <t1;t2>
  #print(t1,'-',t2) DEBUG
  play_block(t1,t2)
  while True:  # check/adjust the block untill it's ok
    y = input(' ENTER=ok, go on / S(kip block) / +- secs to adjust and replay / 0 to replay>')
    if y == '':
      L1_markers.append(t2)
      break
    elif y == '0':
      play_block(t1,t2)
      continue
    elif y.upper() == 'S':
      SKIP_markers.append(t2)
      L1_markers.append(t2)
      break
    elif y[0] in '+-':
      t2 = round(t2 + float(y),2)
      play_block(t1,t2)
      continue
    
  t1 = t2   # start of new interval
  if y.upper() != 'S': i = i + 1 # next block only if audion= not Skipped
print('L1_markers =',L1_markers)
print('SKIP_markers =',SKIP_markers)
y = input('Replace the content of .jkr file? Y/N)>')
if y.upper() == 'Y':
  file_jkr =open(AUDIOfile[0:len(AUDIOfile)-4]+'.jkr','w')
  file_jkr.write('DURATION =' + str(DURATION) + '\n')
  file_jkr.write('L1_markers =' + str(L1_markers)+ '\n')
  file_jkr.write('SKIP_markers =' + str(SKIP_markers)+ '\n')
  file_jkr.close()
