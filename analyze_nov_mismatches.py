"""
Cross-reference DB mismatched rows with the xlsx source of truth.
Read-only: prints a classification + proposed fix, does not modify DB.
"""
import openpyxl
import sys
import io
from collections import defaultdict
sys.stdout = io.TextIOWrapper(sys.stdout.buffer, encoding='utf-8')

XLSX = r'C:\Users\Pratama\Downloads\11\combined_november_2025.xlsx'

# Mismatched rows from DB (id_production, product, tanggal, shift, mesin, qty_ok, header, detail, diff)
db_rows = [
 ('2511030706351473571','157415000','2025-11-03',2,5,2600,2000,2,1998),
 ('251111072252860','157530000','2025-11-11',3,58,4000,300,600,-300),
 ('2511250739051610','168248002','2025-11-25',1,57,5500,100,200,-100),
 ('2511230738361574','168793000','2025-11-23',1,55,1200,50,100,-50),
 ('2511180724041143855','168854300','2025-11-18',2,62,1450,65,130,-65),
 ('251102070633797','168868100','2025-11-02',1,49,16000,60,120,-60),
 ('251103070641797','168868100','2025-11-03',1,49,9000,50,100,-50),
 ('2511040706491784','170300802','2025-11-04',1,47,1000,50,100,-50),
 ('2511040706491784648','170300802','2025-11-04',3,47,900,50,0,50),
 ('2511290739431502','171933000','2025-11-29',1,4,1400,100,200,-100),
 ('2511300739591502','171933000','2025-11-30',3,4,2100,50,100,-50),
 ('2511220738311824945','172348000','2025-11-22',2,60,2200,300,600,-300),
 ('2511230738381824','172348000','2025-11-23',1,60,2800,80,160,-80),
 ('2511250739061251','172602800','2025-11-25',1,58,1050,16,32,-16),
 ('2511020706321599','173114701','2025-11-02',1,39,3250,150,300,-150),
 ('2511230738351484','176658301','2025-11-23',1,37,9500,200,400,-200),
 ('2511040706521782','179894600','2025-11-04',1,68,12600,32,64,-32),
 ('2511050707021782','179894600','2025-11-05',1,68,25200,64,128,-64),
 ('2511060707141782','179894600','2025-11-06',1,68,10900,32,64,-32),
 ('2511070707241782','179894600','2025-11-07',1,68,25200,64,128,-64),
 ('2511100707481782205','179894600','2025-11-10',3,68,25200,64,128,-64),
 ('2511110722521782754','179894600','2025-11-11',3,68,25200,64,128,-64),
 ('2511120722591782666','179894600','2025-11-12',3,68,25200,64,128,-64),
 ('2511170723521782','179894600','2025-11-17',2,68,15000,32,64,-32),
 ('2511180724061782484','179894600','2025-11-18',2,68,25200,64,128,-64),
 ('2511200724301782','179894600','2025-11-20',2,68,11000,32,64,-32),
 ('2511240738551782','179894600','2025-11-24',1,68,18000,32,64,-32),
 ('2511280739411782','179894600','2025-11-28',1,68,5700,32,64,-32),
 ('2511290739541782','179894600','2025-11-29',1,68,25200,64,128,-64),
 ('251111072251998895','180695401','2025-11-11',3,53,620,50,100,-50),
 ('251123073840881','182990400','2025-11-23',1,62,2000,100,200,-100),
 ('2511020706321103','183437601','2025-11-02',1,36,900,50,100,-50),
 ('2511260739121759','188138800','2025-11-26',1,48,4750,50,100,-50),
 ('2511290739521759','188138800','2025-11-29',1,48,4250,100,200,-100),
 ('2511230738321676','188143500','2025-11-23',1,2,4250,200,400,-200),
 ('2511010706251363485','JK116783-07508M','2025-11-01',2,42,620,50,100,-50),
 ('2511220738291348638','JK116783-07608M','2025-11-22',2,50,450,150,300,-150),
 ('2511170723501540507','VCS0180','2025-11-17',2,55,3750,100,200,-100),
 ('2511040706451094841','VFG0350','2025-11-04',2,15,190,12,5,7),
 ('2511180723551094972','VFG0350','2025-11-18',2,15,190,12,0,12),
 ('2511180723551094','VFG0350','2025-11-18',3,15,190,12,0,12),
 ('2511190724091094278','VFG0350','2025-11-19',2,15,190,12,0,12),
 ('2511190724091094670','VFG0350','2025-11-19',3,15,190,12,4,8),
 ('2511040706511809','WV17690','2025-11-04',1,55,600,20,40,-20),
 ('2511050707001809','WV17690','2025-11-05',1,55,800,20,40,-20),
 ('2511060707121809','WV17690','2025-11-06',1,55,900,10,20,-10),
 ('2511070707221809','WV17690','2025-11-07',1,55,500,10,20,-10),
 ('2511130723101809640','WV17690','2025-11-13',3,59,800,20,40,-20),
 ('2511110722521810128','WZ86540','2025-11-11',3,59,900,10,20,-10),
 ('2511210738143127','YT1320031000','2025-11-21',3,8,3000,1320,1,1319),
 ('251103070642807','ZY89870','2025-11-03',1,65,500,20,40,-20),
 ('251117072351807','ZY89870','2025-11-17',2,59,850,20,40,-20),
 ('251118072404807','ZY89870','2025-11-18',2,59,900,10,20,-10),
 ('251119072418807','ZY89870','2025-11-19',2,59,800,30,60,-30),
 ('251120072429807','ZY89870','2025-11-20',2,59,500,20,40,-20),
 ('251122073831807','ZY89870','2025-11-22',2,59,600,30,60,-30),
]

wb = openpyxl.load_workbook(XLSX, data_only=True)
ws = wb.active
headers = [c.value for c in ws[1]]
def idx(n):
    try: return headers.index(n)
    except: return -1

H = {
  'pid': idx('Product ID'),
  'date': idx('Date') if idx('Date') >= 0 else idx('Tanggal'),
  'shift': idx('Shift'),
  'mc': idx('Mesin') if idx('Mesin') >= 0 else idx('Machine'),
  'ok': idx('OK'),
  'td': idx('Total Defect'),
}
ng_cols = [(i,h) for i,h in enumerate(headers) if isinstance(h,str) and (h.startswith('NG-') or h == 'NG Start Up')]

# Index xlsx by (product_id, date_str, shift, mesin)
xlsx_map = {}
for row in ws.iter_rows(min_row=2, values_only=True):
    pid = str(row[H['pid']]).strip() if row[H['pid']] else ''
    d = row[H['date']]
    if hasattr(d, 'strftime'):
        date_str = d.strftime('%Y-%m-%d')
    else:
        date_str = str(d)[:10] if d else ''
    sh = row[H['shift']]
    mc = row[H['mc']]
    if not pid or not date_str or sh is None or mc is None:
        continue
    key = (pid, date_str, int(sh), int(mc))
    td = row[H['td']] or 0
    ng_breakdown = {}
    for i, name in ng_cols:
        v = row[i]
        if v is not None and v != 0:
            try:
                ng_breakdown[name] = int(v)
            except: pass
    ok = row[H['ok']] or 0
    xlsx_map[key] = {'td': int(td), 'ng': ng_breakdown, 'ok': int(ok) if ok else 0}

# Classify each DB mismatch
print(f"{'id_production':<22} {'product':<20} {'date':<11} {'sh':<2} {'mc':<3} {'ok':<6} {'dbHdr':<5} {'dbDet':<5} {'xlsxTD':<6} {'xlsxNG':<6} {'verdict'}")
print('-'*140)
actions = []  # (id_production, action_type, detail or header, new_value, reason)
for r in db_rows:
    id_p, pid, date, sh, mc, ok, hdr, det, diff = r
    key = (pid, date, sh, mc)
    x = xlsx_map.get(key)
    if not x:
        # try with different product_id case
        for k in xlsx_map:
            if k[1]==date and k[2]==sh and k[3]==mc and str(k[0]).strip()==pid.strip():
                x = xlsx_map[k]; break
    if not x:
        print(f"{id_p:<22} {pid:<20} {date:<11} {sh:<2} {mc:<3} {ok:<6} {hdr:<5} {det:<5} {'?':<6} {'?':<6} NO_XLSX_MATCH")
        continue
    xtd = x['td']
    xng_sum = sum(x['ng'].values())

    # Decision logic
    # Case 1: xlsx TD == xlsx NG sum (xlsx is self-consistent) — DB should match it
    #   - if xlsx == header, detail is wrong → fix detail to match header (to equal xlsx)
    #   - if xlsx == detail, header is wrong → fix header to match detail (to equal xlsx)
    #   - if xlsx matches neither → weird, need manual
    # Case 2: xlsx TD != xlsx NG sum (xlsx itself inconsistent)
    #   - trust TD (earlier analysis confirmed TD is correct)
    #   - align DB header and detail to TD

    verdict = ''
    if xtd == xng_sum:
        # xlsx self-consistent
        if xtd == hdr and xtd != det:
            verdict = f"xlsx={xtd}=hdr; fix detail→{xtd}"
            actions.append(('fix_detail', id_p, x['ng'], hdr, 'xlsx matches header'))
        elif xtd == det and xtd != hdr:
            verdict = f"xlsx={xtd}=det; fix header→{xtd}"
            actions.append(('fix_header', id_p, xtd, hdr, 'xlsx matches detail'))
        elif xtd == hdr and xtd == det:
            verdict = 'already-ok?'
        else:
            verdict = f"xlsx={xtd}, hdr={hdr}, det={det} — NEITHER"
    else:
        # xlsx inconsistent too
        verdict = f"xlsx_inconsistent TD={xtd} NG={xng_sum}; hdr={hdr} det={det}"
        # Pick the side that matches TD
        if hdr == xtd:
            verdict += f" → fix detail→{xtd}"
            actions.append(('fix_detail', id_p, x['ng'], hdr, 'TD matches header'))
        elif det == xtd:
            verdict += f" → fix header→{xtd}"
            actions.append(('fix_header', id_p, xtd, hdr, 'TD matches detail'))
        else:
            # TD matches neither DB value — use TD as truth
            verdict += f" → use xlsx TD={xtd} for both"
            actions.append(('fix_both', id_p, x['ng'], xtd, 'TD is truth'))

    print(f"{id_p:<22} {pid:<20} {date:<11} {sh:<2} {mc:<3} {ok:<6} {hdr:<5} {det:<5} {xtd:<6} {xng_sum:<6} {verdict}")

# Summary of actions
from collections import Counter
summary = Counter(a[0] for a in actions)
print('\n=== ACTION SUMMARY ===')
for k, c in summary.items(): print(f'  {k}: {c}')
print(f'  total actions: {len(actions)}')
print(f'  rows with NO action needed: {len(db_rows)-len(actions)}')
