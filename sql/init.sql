CREATE TABLE uch (
id serial NOT NULL PRIMARY KEY,
name text NOT NULL,
address text,
address_fact text,
pol_m boolean,
birthday date,
pasp_ser decimal(4),
pasp_num decimal(6),
pasp_date date,
pasp_who text,
remark text
);

CREATE TABLE acc_type (
id serial NOT NULL PRIMARY KEY,
name text NOT NULL
);

CREATE TABLE acc (
id serial NOT NULL PRIMARY KEY,
uch_id integer NOT NULL,
type_id integer NOT NULL,
creat_date date NOT NULL,
clos_date date,
remark text,
CONSTRAINT acc_uch_id FOREIGN KEY (uch_id) REFERENCES uch ("id") ON DELETE RESTRICT ON UPDATE CASCADE,
CONSTRAINT acc_type_id FOREIGN KEY (type_id) REFERENCES acc_type (id) ON DELETE RESTRICT ON UPDATE CASCADE
);

CREATE TABLE transactsii (
id serial NOT NULL PRIMARY KEY,
remark text NOT NULL
);

CREATE TABLE provodki (
id serial NOT NULL PRIMARY KEY,
cred_acc_id integer NOT NULL,
deb_acc_id integer NOT NULL,
summa money NOT NULL,
exec_date date NOT NULL,
purpose text,
transact_id integer,
CONSTRAINT provodki_cred_acc_id FOREIGN KEY (cred_acc_id) REFERENCES acc (id) ON DELETE RESTRICT ON UPDATE CASCADE,
CONSTRAINT provodki_deb_acc_id FOREIGN KEY (deb_acc_id) REFERENCES acc (id) ON DELETE RESTRICT ON UPDATE CASCADE,
CONSTRAINT provodki_transact_id FOREIGN KEY (transact_id) REFERENCES transactsii (id) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE fund (
acc_id integer PRIMARY KEY,
donation_acc_type_id integer,
expenditure_acc_type_id integer,
plan_donation_acc_type_id integer,
plan_acc_id integer,
extra_donation_acc_type_id integer,
CONSTRAINT fund_acc_id FOREIGN KEY (acc_id) REFERENCES acc (id) ON DELETE CASCADE ON UPDATE CASCADE,
CONSTRAINT fund_donation_acc_type_id FOREIGN KEY (donation_acc_type_id) REFERENCES acc_type (id) ON DELETE RESTRICT ON UPDATE CASCADE,
CONSTRAINT fund_expenditure_acc_type_id FOREIGN KEY (expenditure_acc_type_id) REFERENCES acc_type (id) ON DELETE RESTRICT ON UPDATE CASCADE,
CONSTRAINT fund_plan_donation_acc_type_id FOREIGN KEY (plan_donation_acc_type_id) REFERENCES acc_type (id) ON DELETE RESTRICT ON UPDATE CASCADE,
CONSTRAINT fund_extra_donation_acc_type_id FOREIGN KEY (extra_donation_acc_type_id) REFERENCES acc_type (id) ON DELETE RESTRICT ON UPDATE CASCADE,
CONSTRAINT fund_plan_acc_id FOREIGN KEY (plan_acc_id) REFERENCES acc (id) ON DELETE RESTRICT ON UPDATE CASCADE
);

CREATE TABLE loan_agr (
base_debt_acc integer PRIMARY KEY,
sum money NOT NULL,
base_rate numeric NOT NULL,
fuflo_rate numeric NOT NULL,
fuflo_debt_acc integer,
int_acc integer,
CONSTRAINT loan_agr_base_debt_acc FOREIGN KEY (base_debt_acc) REFERENCES acc (id) ON DELETE CASCADE ON UPDATE CASCADE,
CONSTRAINT loan_agr_fuflo_debt_acc FOREIGN KEY (fuflo_debt_acc) REFERENCES acc (id) ON DELETE RESTRICT ON UPDATE CASCADE,
CONSTRAINT loan_agr_int_acc FOREIGN KEY (int_acc) REFERENCES acc (id) ON DELETE RESTRICT ON UPDATE CASCADE
);

CREATE TABLE garant (
base_debt_acc integer NOT NULL,
uch_id integer NOT NULL,
PRIMARY KEY(base_debt_acc, uch_id),
CONSTRAINT garant_uch_id FOREIGN KEY (uch_id) REFERENCES uch (id) ON DELETE RESTRICT ON UPDATE CASCADE,
CONSTRAINT garant_base_debt_acc FOREIGN KEY (base_debt_acc) REFERENCES loan_agr (base_debt_acc) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE sched (
id serial NOT NULL PRIMARY KEY,
base_debt_acc integer NOT NULL, 
reason integer NOT NULL, 
date date NOT NULL,
CONSTRAINT sched_base_debt_acc FOREIGN KEY (base_debt_acc) REFERENCES loan_agr (base_debt_acc) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE sched_line (
sched_id integer NOT NULL,
date date NOT NULL,
base_debt money NOT NULL,
int money NOT NULL,
remainder money NOT NULL,
CONSTRAINT sched_line_pk PRIMARY KEY (sched_id, date),
CONSTRAINT sched_line_sched_id FOREIGN KEY (sched_id) REFERENCES sched (id) ON DELETE RESTRICT ON UPDATE CASCADE
);

CREATE OR REPLACE FUNCTION ins_loan_agr (p_uch_id integer, p_creat_date date, p_remark text
			     ,p_sum money, p_base_rate numeric, p_fuflo_rate numeric) 
RETURNS void
AS $$
BEGIN
	WITH ins_acc AS (
		INSERT INTO acc (uch_id, type_id, creat_date, remark)
		SELECT p_uch_id, id, p_creat_date, p_remark
		FROM acc_type
		WHERE name = 'Ссудный'
		RETURNING id as acc_id
	)
	INSERT INTO loan_agr (base_debt_acc, sum, base_rate, fuflo_rate) 
	SELECT acc_id, p_sum, p_base_rate, p_fuflo_rate
	FROM ins_acc;
END;
$$ LANGUAGE plpgsql;

CREATE OR REPLACE FUNCTION upd_loan_agr (p_id integer, p_creat_date date, p_clos_date date, p_remark text
                             , p_sum money, p_base_rate numeric, p_fuflo_rate numeric)
RETURNS void
AS $$
BEGIN
	UPDATE acc
	SET creat_date = p_creat_date, clos_date = p_clos_date, remark = p_remark 
	WHERE id = p_id;

	UPDATE loan_agr 
	SET sum = p_sum, base_rate = p_base_rate, fuflo_rate = p_fuflo_rate
	WHERE base_debt_acc = p_id;
END;
$$ LANGUAGE plpgsql;

CREATE OR REPLACE FUNCTION del_loan_agr (p_id integer)
RETURNS void
AS $$
BEGIN
	DELETE FROM acc
	WHERE id in (
		SELECT p_id
		UNION ALL
		SELECT fuflo_debt_acc
		FROM loan_agr
		WHERE base_debt_acc = p_id
		UNION ALL
		SELECT int_acc
		FROM loan_agr
		WHERE base_debt_acc = p_id
	);
END;
$$ LANGUAGE plpgsql;
