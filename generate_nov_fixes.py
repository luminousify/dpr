"""
Generate SQL fix plan for Nov 2025 defect mismatches.
Strategy:
- For each mismatched id_production, compute delta = header - current_detail_sum
- If delta < 0 (detail over-counted, common "2x doubling"): scale detail rows by header/current_sum
  - For rows where scaling is cleanly integer, use straight UPDATE; others treated as add-balance
- If delta > 0: INSERT one 'Balance' NG row with qty=delta to close the gap

Outputs:
- rollback snapshot table DDL
- UPDATE/INSERT statements wrapped in transaction
"""
import openpyxl, io, sys
sys.stdout = io.TextIOWrapper(sys.stdout.buffer, encoding='utf-8')

# DB detail breakdown for all 56 mismatched id_productions (from prior query)
db_detail = {
 '2511010706251363485': [('Void','Process',100,66994)],
 '2511020706321103':    [('Flash','Process',50,67028),('Silver','Appearance',50,67029)],
 '2511020706321599':    [('Short Shoot','Process',300,67033)],
 '251102070633797':     [('Short Shoot','Process',120,67036)],
 '2511030706351473571': [('Crack','Process',1,67045),('Void','Process',1,67046)],
 '251103070641797':     [('Short Shoot','Process',100,67070)],
 '251103070642807':     [('Silver','Appearance',20,67077),('Sink Mark','Appearance',20,67078)],
 '2511040706451094841': [('Silver','Appearance',5,67092)],
 '2511040706491784':    [('Dirty','Appearance',100,67108)],
 '2511040706491784648': [],
 '2511040706511809':    [('Silver','Appearance',40,67112)],
 '2511040706521782':    [('Short Shoot','Process',64,67121)],
 '2511050707001809':    [('Hangus','Process',20,67160),('Silver','Appearance',20,67161)],
 '2511050707021782':    [('Short Shoot','Process',128,67171)],
 '2511060707121809':    [('Silver','Appearance',20,67232)],
 '2511060707141782':    [('Short Shoot','Process',64,67245)],
 '2511070707221809':    [('Hangus','Process',20,67288)],
 '2511070707241782':    [('Short Shoot','Process',128,67296)],
 '2511100707481782205': [('Short Shoot','Process',128,67420)],
 '251111072251998895':  [('Scratch','Appearance',100,67478)],
 '2511110722521782754': [('Short Shoot','Process',128,67489)],
 '2511110722521810128': [('Silver','Appearance',20,67483)],
 '251111072252860':     [('Short Shoot','Process',600,67480)],
 '2511120722591782666': [('Short Shoot','Process',128,67548)],
 '2511130723101809640': [('Sink Mark','Appearance',40,67615)],
 '2511170723501540507': [('Dent','Appearance',200,67798)],
 '251117072351807':     [('Silver','Appearance',40,67805)],
 '2511170723521782':    [('Short Shoot','Process',64,67808)],
 '2511180724041143855': [('Silver','Appearance',130,67866)],
 '251118072404807':     [('Silver','Appearance',20,67864)],
 '2511180724061782484': [('Short Shoot','Process',128,67873)],
 '2511180723551094972': [],
 '2511180723551094':    [],
 '2511190724091094278': [],
 '2511190724091094670': [('Sink Mark','Appearance',4,67895)],
 '251119072418807':     [('Hangus','Process',60,67928)],
 '251120072429807':     [('Hangus','Process',40,67977)],
 '2511200724301782':    [('Short Shoot','Process',64,67979)],
 '2511210738143127':    [('Hangus','Process',1,68014)],
 '2511220738291348638': [('Blackdot','Appearance',300,68117)],
 '2511220738311824945': [('Short Shoot','Process',200,68131),('Silver','Appearance',400,68132)],
 '251122073831807':     [('Hangus','Process',60,68127)],
 '2511230738321676':    [('Blackdot','Appearance',400,68139)],
 '2511230738351484':    [('Flash','Process',400,68162)],
 '2511230738361574':    [('Flash','Process',100,68168)],
 '2511230738381824':    [('Silver','Appearance',160,68175)],
 '251123073840881':     [('Short Shoot','Process',200,68178)],
 '2511240738551782':    [('Short Shoot','Process',64,68217)],
 '2511250739051610':    [('Flash','Process',100,68278),('Silver','Appearance',100,68279)],
 '2511250739061251':    [('Short Shoot','Process',32,68282)],
 '2511260739121759':    [('Blackdot','Appearance',100,68347)],
 '2511280739411782':    [('Short Shoot','Process',64,68453)],
 '2511290739431502':    [('Crack','Process',200,68460)],
 '2511290739521759':    [('Flash','Process',200,68505)],
 '2511290739541782':    [('Short Shoot','Process',128,68520)],
 '2511300739591502':    [('Crack','Process',100,68561)],
}

# Header values (target)
target = {
 '2511010706251363485':50,'2511020706321103':50,'2511020706321599':150,'251102070633797':60,
 '2511030706351473571':2000,'251103070641797':50,'251103070642807':20,'2511040706451094841':12,
 '2511040706491784':50,'2511040706491784648':50,'2511040706511809':20,'2511040706521782':32,
 '2511050707001809':20,'2511050707021782':64,'2511060707121809':10,'2511060707141782':32,
 '2511070707221809':10,'2511070707241782':64,'2511100707481782205':64,'251111072251998895':50,
 '2511110722521782754':64,'2511110722521810128':10,'251111072252860':300,'2511120722591782666':64,
 '2511130723101809640':20,'2511170723501540507':100,'251117072351807':20,'2511170723521782':32,
 '2511180724041143855':65,'251118072404807':10,'2511180724061782484':64,'2511180723551094972':12,
 '2511180723551094':12,'2511190724091094278':12,'2511190724091094670':12,'251119072418807':30,
 '251120072429807':20,'2511200724301782':32,'2511210738143127':1320,'2511220738291348638':150,
 '2511220738311824945':300,'251122073831807':30,'2511230738321676':200,'2511230738351484':200,
 '2511230738361574':50,'2511230738381824':80,'251123073840881':100,'2511240738551782':32,
 '2511250739051610':100,'2511250739061251':16,'2511260739121759':50,'2511280739411782':32,
 '2511290739431502':100,'2511290739521759':100,'2511290739541782':64,'2511300739591502':50,
}

# Classify + emit SQL
updates = []  # (id_DL, new_qty, old_qty, id_production, reason)
inserts = []  # (id_production, nama, kategori, qty)
summary_halve = 0
summary_insert = 0
for idp, rows in db_detail.items():
    cur_sum = sum(q for _,_,q,_ in rows)
    tgt = target[idp]
    delta = tgt - cur_sum
    if delta == 0:
        continue  # nothing to do (shouldn't happen in this list)
    if delta < 0 and cur_sum > 0:
        # Need to scale down. Check if each row scales to integer.
        ratio = tgt / cur_sum  # e.g., 0.5 for doubling case
        can_scale_cleanly = all(int(q * ratio) == q * ratio for _,_,q,_ in rows)
        if can_scale_cleanly:
            for nama, kat, q, id_dl in rows:
                new_q = int(q * ratio)
                updates.append((id_dl, new_q, q, idp, f'scale {ratio}'))
            summary_halve += 1
            continue
        # Fallback: adjust by inserting negative balance — but qty must be positive, so
        # just halve proportionally and round (we'll prefer straight halve)
        # For now, emit halving with rounding; user can review.
        for nama, kat, q, id_dl in rows:
            new_q = int(q * ratio)
            updates.append((id_dl, new_q, q, idp, f'scale {ratio} (rounded)'))
        summary_halve += 1
    else:
        # delta > 0 or cur_sum == 0: INSERT a Balance row
        inserts.append((idp, 'Balance', 'Process', delta))
        summary_insert += 1

print(f'-- Fix plan summary: {summary_halve} rows will have detail scaled; {summary_insert} rows will get a new Balance NG row')
print(f'-- Total UPDATEs: {len(updates)}')
print(f'-- Total INSERTs: {len(inserts)}')
print()
print('-- ROLLBACK SNAPSHOT')
print('DROP TABLE IF EXISTS _rb_dl_nov2025_full;')
print('CREATE TABLE _rb_dl_nov2025_full AS')
idps = sorted(set(db_detail.keys()))
idps_sql = ",\n  ".join(f"'{i}'" for i in idps)
print("SELECT dl.id_DL, dl.id_production, dl.nama, dl.kategori, dl.type, dl.satuan, dl.qty, NOW() AS snap_ts")
print("FROM t_production_op_dl dl")
print(f"WHERE dl.id_production IN (\n  {idps_sql}\n) AND dl.type='NG';")
print()
print('-- BEGIN TRANSACTION')
print('START TRANSACTION;')
for id_dl, new_q, old_q, idp, reason in updates:
    print(f"UPDATE t_production_op_dl SET qty={new_q} WHERE id_DL={id_dl}; -- {idp} {old_q}→{new_q} ({reason})")
for idp, nama, kat, q in inserts:
    print(f"INSERT INTO t_production_op_dl (id_production, nama, kategori, type, satuan, qty) VALUES ('{idp}','{nama}','{kat}','NG','Pcs',{q}); -- {idp} add-balance qty={q}")
print("""
-- Verify: no more Nov 2025 mismatches for these ids
SELECT COUNT(*) AS remaining_mismatches
FROM t_production_op p
JOIN select_bom b ON b.id_bom=p.id_bom
LEFT JOIN (SELECT id_production, SUM(qty) AS sum_dl FROM t_production_op_dl WHERE type='NG' GROUP BY id_production) dl
  ON dl.id_production=p.id_production
WHERE YEAR(p.tanggal)=2025 AND MONTH(p.tanggal)=11
  AND p.qty_ng <> IFNULL(dl.sum_dl,0);
""")
print('COMMIT;')
print('SELECT "commit complete" AS status;')
