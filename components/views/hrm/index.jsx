import React from 'react';
import {createRoot} from 'react-dom/client';

import { MountPoint } from 'crewhrm-materials/mountpoint.jsx';
import { HRM } from './hrm.jsx';
import { data_pointer, getElementDataSet } from 'crewhrm-materials/helpers.jsx';
import { Promote } from '../../promote/promote.jsx';
import { WpDashboardFullPage } from 'crewhrm-materials/backend-dashboard-container/full-page-container.jsx';

// Dashboard
const hrm = document.getElementById('crewhrm_dashboard');
if (hrm) {
    createRoot(hrm).render(
        <MountPoint element={hrm}>
            <HRM {...getElementDataSet(hrm)} />
        </MountPoint>
    );
}

// All Jobs
const jobs = document.getElementById('crewhrm_dashboard_all_jobs');
if(jobs) {
	createRoot(jobs).render(
		<MountPoint>
			<HRM {...getElementDataSet(jobs)} is_all_job_page={true}/>
		</MountPoint>
	)
}

// Register calendar page promotion
if ( ! window[data_pointer].has_pro ) {
	const calendar = document.getElementById('crewhrm_event_calendar_page_promotion');
	if ( calendar ) {
		createRoot(calendar).render(
			<MountPoint>
				<WpDashboardFullPage>
					<div className={'position-relative'.classNames()} style={{minHeight: '600px'}}>
						<Promote content='calendar'/>
					</div>
				</WpDashboardFullPage>
			</MountPoint>
		);
	}
}
