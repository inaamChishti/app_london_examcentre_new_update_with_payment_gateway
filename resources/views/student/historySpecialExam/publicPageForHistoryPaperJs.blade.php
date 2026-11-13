<script>
    // History routes data
    const historyRoutes = {
       'A': {
           name: 'Conquest, control and resistance in the medieval world',
           paper1: '1A: The crusades, c1095–1204',
           paper2: [
               '2A.1: Anglo-Saxon England and the Anglo-Norman Kingdom, c1053–1106',
               '2A.2: England and the Angevin Empire in the reign of Henry II, 1154–89'
           ],
           paper3: [
               { name: '30: Lancastrians, Yorkists and Henry VII, 1399–1509', code: 'A0' },
               { name: '31: Rebellion and disorder under the Tudors, 1485–1603', code: 'A1' },
               { name: '32: The Golden Age of Spain, 1474–1598', code: 'A2' },
               { name: '33: The witch craze in Britain, Europe and North America, c1580–c1750', code: 'A3' },
               { name: '34.1: Industrialisation and social change in Britain, 1759–1928: forging a new society<br>34.2: Poverty, public health and the state in Britain, c1780–1939', code: 'A4', subOptions: ['34.1: Industrialisation and social change in Britain, 1759–1928: forging a new society', '34.2: Poverty, public health and the state in Britain, c1780–1939'] },
               { name: '35.1: Britain: losing and gaining an empire, 1763–1914<br>35.2: The British experience of warfare, c1790–1918', code: 'A5', subOptions: ['35.1: Britain: losing and gaining an empire, 1763–1914', '35.2: The British experience of warfare, c1790–1918'] },
               { name: '36.1: Protest, agitation and parliamentary reform in Britain, c1780–1928<br>36.2: Ireland and the Union, c1774–1923', code: 'A6', subOptions: ['36.1: Protest, agitation and parliamentary reform in Britain, c1780–1928', '36.2: Ireland and the Union, c1774–1923'] },
               { name: '37.1: The changing nature of warfare, 1859–1991: perception and reality<br>37.2: Germany, 1871–1990: united, divided and reunited', code: 'A7', subOptions: ['37.1: The changing nature of warfare, 1859–1991: perception and reality', '37.2: Germany, 1871–1990: united, divided and reunited'] },
               { name: '38.1: The making of modern Russia, 1855–1991<br>38.2: The making of modern China, 1860–1997', code: 'A8', subOptions: ['38.1: The making of modern Russia, 1855–1991', '38.2: The making of modern China, 1860–1997'] },
               { name: '39.1: Civil rights and race relations in the USA, 1850–2009<br>39.2: Mass media and social change in Britain, 1882–2004', code: 'A9', subOptions: ['39.1: Civil rights and race relations in the USA, 1850–2009', '39.2: Mass media and social change in Britain, 1882–2004'] }
           ]
       },
       'B': {
           name: 'Religion and the state in early modern Europe',
           paper1: '1B: England, 1509–1603: authority, nation and religion',
           paper2: [
               '2B.1: Luther and the German Reformation, c1515–55',
               '2B.2: The Dutch Revolt, c1563–1609'
           ],
           paper3: [
               { name: '30: Lancastrians, Yorkists and Henry VII, 1399–1509', code: 'B0' },
               { name: '33: The witch craze in Britain, Europe and North America, c1580–c1750', code: 'B3' },
               { name: '34.1: Industrialisation and social change in Britain, 1759–1928: forging a new society<br>34.2: Poverty, public health and the state in Britain, c1780–1939', code: 'B4', subOptions: ['34.1: Industrialisation and social change in Britain, 1759–1928: forging a new society', '34.2: Poverty, public health and the state in Britain, c1780–1939'] },
               { name: '35.1: Britain: losing and gaining an empire, 1763–1914<br>35.2: The British experience of warfare, c1790–1918', code: 'B5', subOptions: ['35.1: Britain: losing and gaining an empire, 1763–1914', '35.2: The British experience of warfare, c1790–1918'] },
               { name: '36.1: Protest, agitation and parliamentary reform in Britain, c1780–1928<br>36.2: Ireland and the Union, c1774–1923', code: 'B6', subOptions: ['36.1: Protest, agitation and parliamentary reform in Britain, c1780–1928', '36.2: Ireland and the Union, c1774–1923'] },
               { name: '37.1: The changing nature of warfare, 1859–1991: perception and reality<br>37.2: Germany, 1871–1990: united, divided and reunited', code: 'B7', subOptions: ['37.1: The changing nature of warfare, 1859–1991: perception and reality', '37.2: Germany, 1871–1990: united, divided and reunited'] },
               { name: '38.1: The making of modern Russia, 1855–1991<br>38.2: The making of modern China, 1860–1997', code: 'B8', subOptions: ['38.1: The making of modern Russia, 1855–1991', '38.2: The making of modern China, 1860–1997'] },
               { name: '39.1: Civil rights and race relations in the USA, 1850–2009<br>39.2: Mass media and social change in Britain, 1882–2004', code: 'B9', subOptions: ['39.1: Civil rights and race relations in the USA, 1850–2009', '39.2: Mass media and social change in Britain, 1882–2004'] }
           ]
       },
       'C': {
           name: 'Revolutions in early modern and modern Europe',
           paper1: '1C: Britain, 1625–1701: conflict, revolution and settlement',
           paper2: [
               '2C.1: France in revolution, 1774–99',
               '2C.2: Russia in revolution, 1894–1924'
           ],
           paper3: [
               { name: '30: Lancastrians, Yorkists and Henry VII, 1399–1509', code: 'C0' },
               { name: '31: Rebellion and disorder under the Tudors, 1485–1603', code: 'C1' },
               { name: '32: The Golden Age of Spain, 1474–1598', code: 'C2' },
               { name: '33: The witch craze in Britain, Europe and North America, c1580–c1750', code: 'C3' },
               { name: '34.1: Industrialisation and social change in Britain, 1759–1928: forging a new society<br>34.2: Poverty, public health and the state in Britain, c1780–1939', code: 'C4', subOptions: ['34.1: Industrialisation and social change in Britain, 1759–1928: forging a new society', '34.2: Poverty, public health and the state in Britain, c1780–1939'] },
               { name: '35.1: Britain: losing and gaining an empire, 1763–1914<br>35.2: The British experience of warfare, c1790–1918', code: 'C5', subOptions: ['35.1: Britain: losing and gaining an empire, 1763–1914', '35.2: The British experience of warfare, c1790–1918'] },
               { name: '36.1: Protest, agitation and parliamentary reform in Britain, c1780–1928<br>36.2: Ireland and the Union, c1774–1923', code: 'C6', subOptions: ['36.1: Protest, agitation and parliamentary reform in Britain, c1780–1928', '36.2: Ireland and the Union, c1774–1923'] },
               { name: '37.1: The changing nature of warfare, 1859–1991: perception and reality<br>37.2: Germany, 1871–1990: united, divided and reunited', code: 'C7', subOptions: ['37.1: The changing nature of warfare, 1859–1991: perception and reality', '37.2: Germany, 1871–1990: united, divided and reunited'] },
               { name: '38.2: The making of modern China, 1860–1997', code: 'C8' },
               { name: '39.1: Civil rights and race relations in the USA, 1850–2009<br>39.2: Mass media and social change in Britain, 1882–2004', code: 'C9', subOptions: ['39.1: Civil rights and race relations in the USA, 1850–2009', '39.2: Mass media and social change in Britain, 1882–2004'] }
           ]
       },
       'D': {
           name: 'Challenges to the authority of the state in the late eighteenth and nineteenth centuries',
           paper1: '1D: Britain, c1785–c1870: democracy, protest and reform',
           paper2: [
               '2D.1: The unification of Italy, c1830–70',
               '2D.2: The unification of Germany, c1840–71'
           ],
           paper3: [
               { name: '30: Lancastrians, Yorkists and Henry VII, 1399–1509', code: 'D0' },
               { name: '31: Rebellion and disorder under the Tudors, 1485–1603', code: 'D1' },
               { name: '32: The Golden Age of Spain, 1474–1598', code: 'D2' },
               { name: '33: The witch craze in Britain, Europe and North America, c1580–c1750', code: 'D3' },
               { name: '37.1: The changing nature of warfare, 1859–1991: perception and reality<br>37.2: Germany, 1871–1990: united, divided and reunited', code: 'D7', subOptions: ['37.1: The changing nature of warfare, 1859–1991: perception and reality', '37.2: Germany, 1871–1990: united, divided and reunited'] },
               { name: '38.1: The making of modern Russia, 1855–1991<br>38.2: The making of modern China, 1860–1997', code: 'D8', subOptions: ['38.1: The making of modern Russia, 1855–1991', '38.2: The making of modern China, 1860–1997'] },
               { name: '39.1: Civil rights and race relations in the USA, 1850–2009<br>39.2: Mass media and social change in Britain, 1882–2004', code: 'D9', subOptions: ['39.1: Civil rights and race relations in the USA, 1850–2009', '39.2: Mass media and social change in Britain, 1882–2004'] }
           ]
       },
       'E': {
           name: 'Communist states in the twentieth century',
           paper1: '1E: Russia, 1917–91: from Lenin to Yeltsin',
           paper2: [
               '2E.1: Mao’s China, 1949–76',
               '2E.2: The German Democratic Republic, 1949–90'
           ],
           paper3: [
               { name: '30: Lancastrians, Yorkists and Henry VII, 1399–1509', code: 'E0' },
               { name: '31: Rebellion and disorder under the Tudors, 1485–1603', code: 'E1' },
               { name: '34.1: Industrialisation and social change in Britain, 1759–1928: forging a new society<br>34.2: Poverty, public health and the state in Britain, c1780–1939', code: 'E4', subOptions: ['34.1: Industrialisation and social change in Britain, 1759–1928: forging a new society', '34.2: Poverty, public health and the state in Britain, c1780–1939'] },
               { name: '35.1: Britain: losing and gaining an empire, 1763–1914<br>35.2: The British experience of warfare, c1790–1918', code: 'E5', subOptions: ['35.1: Britain: losing and gaining an empire, 1763–1914', '35.2: The British experience of warfare, c1790–1918'] },
               { name: '36.1: Protest, agitation and parliamentary reform in Britain, c1780–1928<br>36.2: Ireland and the Union, c1774–1923', code: 'E6', subOptions: ['36.1: Protest, agitation and parliamentary reform in Britain, c1780–1928', '36.2: Ireland and the Union, c1774–1923'] },
               { name: '37.2: Germany, 1871–1990: united, divided and reunited', code: 'E7' },
               { name: '38.2: The making of modern China, 1860–1997', code: 'E8' }
           ]
       },
       'F': {
           name: 'Searching for rights and freedoms in the twentieth century',
           paper1: '1F: In search of the American Dream: the USA, c1917–96',
           paper2: [
               '2F.1: India, c1914–48: the road to independence',
               '2F.2: South Africa, 1948–94: from apartheid state to ‘rainbow nation’'
           ],
           paper3: [
               { name: '30: Lancastrians, Yorkists and Henry VII, 1399–1509', code: 'F0' },
               { name: '31: Rebellion and disorder under the Tudors, 1485–1603', code: 'F1' },
               { name: '34.1: Industrialisation and social change in Britain, 1759–1928: forging a new society<br>34.2: Poverty, public health and the state in Britain, c1780–1939', code: 'F4', subOptions: ['34.1: Industrialisation and social change in Britain, 1759–1928: forging a new society', '34.2: Poverty, public health and the state in Britain, c1780–1939'] },
               { name: '35.1: Britain: losing and gaining an empire, 1763–1914<br>35.2: The British experience of warfare, c1790–1918', code: 'F5', subOptions: ['35.1: Britain: losing and gaining an empire, 1763–1914', '35.2: The British experience of warfare, c1790–1918'] },
               { name: '36.1: Protest, agitation and parliamentary reform in Britain, c1780–1928<br>36.2: Ireland and the Union, c1774–1923', code: 'F6', subOptions: ['36.1: Protest, agitation and parliamentary reform in Britain, c1780–1928', '36.2: Ireland and the Union, c1774–1923'] }
           ]
       },
       'G': {
           name: 'Nationalism, dictatorship and democracy in twentieth-century Europe',
           paper1: '1G: Germany and West Germany, 1918–89',
           paper2: [
               '2G.1: The rise and fall of fascism in Italy, c1911–46',
               '2G.2: Spain, 1930–78: republicanism, Francoism and the re-establishment of democracy'
           ],
           paper3: [
               { name: '30: Lancastrians, Yorkists and Henry VII, 1399–1509', code: 'G0' },
               { name: '31: Rebellion and disorder under the Tudors, 1485–1603', code: 'G1' },
               { name: '34.1: Industrialisation and social change in Britain, 1759–1928: forging a new society<br>34.2: Poverty, public health and the state in Britain, c1780–1939', code: 'G4', subOptions: ['34.1: Industrialisation and social change in Britain, 1759–1928: forging a new society', '34.2: Poverty, public health and the state in Britain, c1780–1939'] },
               { name: '35.1: Britain: losing and gaining an empire, 1763–1914<br>35.2: The British experience of warfare, c1790–1918', code: 'G5', subOptions: ['35.1: Britain: losing and gaining an empire, 1763–1914', '35.2: The British experience of warfare, c1790–1918'] },
               { name: '36.1: Protest, agitation and parliamentary reform in Britain, c1780–1928<br>36.2: Ireland and the Union, c1774–1923', code: 'G6', subOptions: ['36.1: Protest, agitation and parliamentary reform in Britain, c1780–1928', '36.2: Ireland and the Union, c1774–1923'] }
           ]
       },
       'H': {
           name: 'Democracies in change: Britain and the USA in the twentieth century',
           paper1: '1H: Britain transformed, 1918–97',
           paper2: [
               '2H.1: The USA, c1920–55: boom, bust and recovery',
               '2H.2: The USA, 1955–92: conformity and challenge'
           ],
           paper3: [
               { name: '30: Lancastrians, Yorkists and Henry VII, 1399–1509', code: 'H0' },
               { name: '31: Rebellion and disorder under the Tudors, 1485–1603', code: 'H1' },
               { name: '32: The Golden Age of Spain, 1474–1598', code: 'H2' },
               { name: '33: The witch craze in Britain, Europe and North America, c1580–c1750', code: 'H3' },
               { name: '34.1: Industrialisation and social change in Britain, 1759–1928: forging a new society<br>34.2: Poverty, public health and the state in Britain, c1780–1939', code: 'H4', subOptions: ['34.1: Industrialisation and social change in Britain, 1759–1928: forging a new society', '34.2: Poverty, public health and the state in Britain, c1780–1939'] },
               { name: '35.1: Britain: losing and gaining an empire, 1763–1914<br>35.2: The British experience of warfare, c1790–1918', code: 'H5', subOptions: ['35.1: Britain: losing and gaining an empire, 1763–1914', '35.2: The British experience of warfare, c1790–1918'] },
               { name: '36.1: Protest, agitation and parliamentary reform in Britain, c1780–1928<br>36.2: Ireland and the Union, c1774–1923', code: 'H6', subOptions: ['36.1: Protest, agitation and parliamentary reform in Britain, c1780–1928', '36.2: Ireland and the Union, c1774–1923'] }
           ]
       }
   };

   function updateCourseTypes(selectElement) {
       const board = selectElement.value;
       const row = selectElement.closest('tr');
       const courseTypeSelect = row.querySelector('.course-type');
       const subjectSelect = row.querySelector('.subject-name');
       const codeInput = row.querySelector('.subject-code');

       courseTypeSelect.innerHTML = '<option value="" disabled selected>Course Type</option>';
       subjectSelect.innerHTML = '<option value="" disabled selected>Subject Name</option>';
       codeInput.value = '';

       if (board && courseTypesByBoard[board]) {
           courseTypesByBoard[board].forEach(courseType => {
               courseTypeSelect.add(new Option(courseType, courseType));
           });
       }

       // Remove appended history section if board changes
       const appendSection = row.closest('.row-container')?.querySelector('.history-details') || row.closest('table')?.nextElementSibling?.querySelector('.history-details');
       if (appendSection) {
           appendSection.remove();
       }
   }

   function updateSubjects(selectElement) {
       const board = selectElement.closest('tr').querySelector('.exam-board').value;
       const courseType = selectElement.value;
       const subjectSelect = selectElement.closest('tr').querySelector('.subject-name');
       const codeInput = selectElement.closest('tr').querySelector('.subject-code');

       subjectSelect.innerHTML = '<option value="" disabled selected>Subject Name</option>';
       codeInput.value = '';

       if (board && courseType && subjectsData[board]?.[courseType]) {
           Object.entries(subjectsData[board][courseType]).forEach(([name, code]) => {
               const option = new Option(name, name);
               option.dataset.code = code;
               subjectSelect.add(option);
           });
       }

       subjectSelect.addEventListener('change', function() {
           const selectedOption = this.options[this.selectedIndex];
           codeInput.value = selectedOption?.dataset?.code || '';
       });

       // Remove appended history section if course type changes
       const appendSection = selectElement.closest('.row-container')?.querySelector('.history-details') || selectElement.closest('table')?.nextElementSibling?.querySelector('.history-details');
       if (appendSection) {
           appendSection.remove();
       }
   }

   function checkHistorySelection(selectElement) {
       const row = selectElement.closest('tr');
       const board = row.querySelector('.exam-board').value;
       const courseType = row.querySelector('.course-type').value;
       const subject = selectElement.value;

       // Remove appended history section if subject changes
       const appendSection = row.closest('.row-container')?.querySelector('.history-details') || row.closest('table')?.nextElementSibling?.querySelector('.history-details');
       if (appendSection) {
           appendSection.remove();
       }

       if (board === 'Pearson' && courseType === 'A Level' && subject === 'History') {
           showHistoryModal(row);
       }
   }

   function showHistoryModal(row, editData = null) {
       const codeInput = row.querySelector('.subject-code');
       const isEdit = !!editData;
       const modalId = `historyModal_${Date.now()}`;

       // Create Bootstrap modal
       const modalHtml = `
           <div class="modal fade" id="${modalId}" tabindex="-1" aria-labelledby="historyModalLabel" aria-hidden="true">
               <div class="modal-dialog modal-lg">
                   <div class="modal-content" style="border-radius: 8px; box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);">
                       <div class="modal-header" style="background: #F87A14; border-bottom: 4px solid #1976D2; color: #fff; border-radius: 8px 8px 0 0; padding: 15px 20px;">
                           <h5 class="modal-title" id="historyModalLabel" style="font-weight: 600; text-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                               <i class="fas fa-book-open me-2"></i>${isEdit ? 'Edit' : 'Select'} History Route and Papers
                           </h5>
                           <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                       </div>
                       <div class="modal-body" style="max-height: 500px; overflow-y: auto; padding: 20px; background: #fff;">
                           <div class="mb-4">
                               <h6 style="font-weight: 600; color: #2c3e50; display: flex; align-items: center;">
                                   <i class="fas fa-route me-2" style="color: #3498db;"></i>Route Selection
                               </h6>
                               <select id="routeSelect" class="form-select" style="width: 100%; padding: 12px; border: 1px solid #b0e0e6; border-radius: 4px; background-color: #f0f8ff;" onfocus="this.style.borderColor='#57afbf'; this.style.boxShadow='0 0 0 0.2rem rgba(87, 175, 191, 0.25)'" onblur="this.style.borderColor='#b0e0e6'; this.style.boxShadow='none'">
                                   <option value="" disabled selected>Select Route</option>
                                   ${Object.keys(historyRoutes).map(route => `<option value="${route}" ${editData && editData.route === route ? 'selected' : ''}>${route}: ${historyRoutes[route].name}</option>`).join('')}
                               </select>
                           </div>

                           <div id="paper1Section" style="display: none;" class="mb-4">
                               <h6 style="font-weight: 600; color: #2c3e50; display: flex; align-items: center;">
                                   <i class="fas fa-file-alt me-2" style="color: #e67e22;"></i>Paper 1 Option
                               </h6>
                               <input type="text" id="paper1" class="form-control" readonly style="width: 100%; padding: 12px; border: 1px solid #b0e0e6; border-radius: 4px; background-color: #f0f8ff;" onfocus="this.style.borderColor='#57afbf'; this.style.boxShadow='0 0 0 0.2rem rgba(87, 175, 191, 0.25)'" onblur="this.style.borderColor='#b0e0e6'; this.style.boxShadow='none'">
                           </div>

                           <div id="paper2Section" style="display: none;" class="mb-4">
                               <h6 style="font-weight: 600; color: #2c3e50; display: flex; align-items: center;">
                                   <i class="fas fa-file-alt me-2" style="color: #2ecc71;"></i>Paper 2 Options (Select One)
                               </h6>
                               <div id="paper2Options" style="padding-left: 15px;"></div>
                           </div>

                           <div id="paper3Section" style="display: none;" class="mb-4">
                               <h6 style="font-weight: 600; color: #2c3e50; display: flex; align-items: center;">
                                   <i class="fas fa-file-alt me-2" style="color: #3498db;"></i>Paper 3 Options (Select One)
                               </h6>
                               <div id="paper3Options" style="max-height: 200px; overflow-y: auto; padding-left: 15px;"></div>
                           </div>
                       </div>
                       <div class="modal-footer" style="padding: 15px 20px; border-top: 1px solid #b0e0e6;">
                           <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" style="background: #e74c3c; border: none; padding: 10px 20px; color: #fff; border-radius: 4px; transition: background 0.3s;" onmouseover="this.style.background='#c0392b'" onmouseout="this.style.background='#e74c3c'">
                               <i class="fas fa-times me-2"></i>Cancel
                           </button>
                           <button type="button" class="btn btn-primary" id="saveHistory" style="background: #57afbfd6; border: none; padding: 10px 20px; color: #fff; border-radius: 4px; transition: background 0.3s;" onmouseover="this.style.background='#4682b4'" onmouseout="this.style.background='#57afbfd6'">
                               <i class="fas fa-save me-2"></i>Save
                           </button>
                       </div>
                   </div>
               </div>
           </div>
       `;

       // Remove any existing modal
       $(`#${modalId}`).remove();
       $('body').append(modalHtml);

       // Show the modal
       $(`#${modalId}`).modal('show');

       // Handle route selection
       $(`#${modalId} #routeSelect`).on('change', function() {
           const route = this.value;
           if (route) {
               const routeData = historyRoutes[route];

               $(`#${modalId} #paper1Section`).show();
               $(`#${modalId} #paper1`).val(routeData.paper1);

               $(`#${modalId} #paper2Section`).show();
               $(`#${modalId} #paper2Options`).html(routeData.paper2.map(option => `
                   <div class="form-check mb-2">
                       <input class="form-check-input" type="radio" name="paper2" value="${option}" id="${option.replace(/\s+/g, '_')}_${modalId}" ${editData && editData.paper2 === option ? 'checked' : ''} style="margin-top: 5px;">
                       <label class="form-check-label" for="${option.replace(/\s+/g, '_')}_${modalId}" style="color: #2c3e50; font-weight: 400;">${option}</label>
                   </div>
               `).join(''));

               $(`#${modalId} #paper3Section`).show();
               $(`#${modalId} #paper3Options`).html(routeData.paper3.map(option => `
                   <div class="form-check mb-2">
                       <input class="form-check-input" type="radio" name="paper3" value="${option.name}" id="${option.name.replace(/\s+/g, '_')}_${modalId}" data-code="${option.code}" data-suboptions="${option.subOptions ? option.subOptions.join('|') : ''}" ${editData && editData.paper3 === option.name ? 'checked' : ''} style="margin-top: 5px;">
                       <label class="form-check-label" for="${option.name.replace(/\s+/g, '_')}_${modalId}" style="color: #2c3e50; font-weight: 400;">${option.name.replace('<br>', '<br>')}</label>
                   </div>
               `).join(''));
           } else {
               $(`#${modalId} #paper1Section, #${modalId} #paper2Section, #${modalId} #paper3Section`).hide();
           }
       });

       // Pre-fill modal if editing
       if (isEdit) {
           $(`#${modalId} #routeSelect`).trigger('change');
       }

       // Handle save button
       $(`#${modalId} #saveHistory`).on('click', function() {
           const route = $(`#${modalId} #routeSelect`).val();
           const paper2 = $(`#${modalId} input[name="paper2"]:checked`).val();
           const paper3 = $(`#${modalId} input[name="paper3"]:checked`);
           const entryCode = paper3.data('code');
           const subOptions = paper3.data('suboptions') ? paper3.data('suboptions').split('|') : [paper3.val()];

           if (!route) {
               Swal.fire({
                   icon: 'warning',
                   title: 'Missing Route',
                   text: 'Please select a route before saving.',
                   confirmButtonColor: '#57afbfd6',
                   confirmButtonText: 'OK'
               });
               return;
           }
           if (!paper2) {
               Swal.fire({
                   icon: 'warning',
                   title: 'Missing Paper 2',
                   text: 'Please select a Paper 2 option before saving.',
                   confirmButtonColor: '#57afbfd6',
                   confirmButtonText: 'OK'
               });
               return;
           }
           if (!paper3.length) {
               Swal.fire({
                   icon: 'warning',
                   title: 'Missing Paper 3',
                   text: 'Please select a Paper 3 option before saving.',
                   confirmButtonColor: '#57afbfd6',
                   confirmButtonText: 'OK'
               });
               return;
           }

           // Remove existing hidden inputs
           row.querySelectorAll('input[name="history_route[]"], input[name="history_paper1[]"], input[name="history_paper2[]"], input[name="history_paper3[]"], input[name="history_paper3_suboptions[]"], input[name="history_entry_code[]"]').forEach(input => input.remove());

           // Add hidden inputs
           row.insertAdjacentHTML('beforeend', `
               <input type="hidden" name="history_route[]" value="${route}">
               <input type="hidden" name="history_paper1[]" value="${historyRoutes[route].paper1}">
               <input type="hidden" name="history_paper2[]" value="${paper2}">
               <input type="hidden" name="history_paper3[]" value="${paper3.val()}">
               ${subOptions.map(opt => `<input type="hidden" name="history_paper3_suboptions[]" value="${opt}">`).join('')}
               <input type="hidden" name="history_entry_code[]" value="${entryCode}">
           `);

           codeInput.value = entryCode;

           // Append or update history details section
           const appendSection = row.closest('.row-container')?.querySelector('.history-details') || row.closest('table')?.nextElementSibling?.querySelector('.history-details');
           const paper3Fields = subOptions.length > 1 ? `
               <td style="padding: 8px; border: 1px solid #b0e0e6;">
                   <input type="text" class="form-control" name="history_paper3_1[]" value="${subOptions[0]}" readonly style="width: 100%; padding: 8px; border: none; background: transparent; font-size: 12px;">
               </td>
               <td style="padding: 8px; border: 1px solid #b0e0e6;">
                   <input type="text" class="form-control" name="history_paper3_2[]" value="${subOptions[1]}" readonly style="width: 100%; padding: 8px; border: none; background: transparent; font-size: 12px;">
               </td>
           ` : `
               <td style="padding: 8px; border: 1px solid #b0e0e6;">
                   <input type="text" class="form-control" name="history_paper3_1[]" value="${subOptions[0]}" readonly style="width: 100%; padding: 8px; border: none; background: transparent; font-size: 12px;">
               </td>
           `;
           const paper3Headers = subOptions.length > 1 ? `
               <th style="padding: 8px; border: 1px solid #b0e0e6; text-align: left;">Paper 3.1</th>
               <th style="padding: 8px; border: 1px solid #b0e0e6; text-align: left;">Paper 3.2</th>
           ` : `
               <th style="padding: 8px; border: 1px solid #b0e0e6; text-align: left;">Paper 3</th>
           `;
           const historyDetailsHtml = `
               <div class="history-details" style="margin-top: 15px; padding: 15px; border: 1px solid #b0e0e6; border-radius: 4px; background: #f0f8ff;">
                   <h6 style="font-weight: 600; color: #2c3e50; display: flex; align-items: center; margin-bottom: 10px;">
                       <i class="fas fa-book-open me-2" style="color: #3498db;"></i>Selected History Papers
                   </h6>
                   <table style="width: 100%; border-collapse: collapse;">
                       <thead>
                           <tr style="background: #e6f3f8; font-weight: 600; color: #2c3e50;">
                               <th style="padding: 8px; border: 1px solid #b0e0e6; text-align: left;">Route</th>
                               <th style="padding: 8px; border: 1px solid #b0e0e6; text-align: left;">Paper 1</th>
                               <th style="padding: 8px; border: 1px solid #b0e0e6; text-align: left;">Paper 2</th>
                               ${paper3Headers}
                               <th style="padding: 8px; border: 1px solid #b0e0e6; text-align: center;">Action</th>
                           </tr>
                       </thead>
                       <tbody>
                           <tr>
                               <td style="padding: 8px; border: 1px solid #b0e0e6;">
                                   <input type="text" class="form-control" name="history_route[]" value="${route}: ${historyRoutes[route].name}" readonly style="width: 100%; padding: 8px; border: none; background: transparent; font-size: 12px;">
                               </td>
                               <td style="padding: 8px; border: 1px solid #b0e0e6;">
                                   <input type="text" class="form-control" name="history_paper1[]" value="${historyRoutes[route].paper1}" readonly style="width: 100%; padding: 8px; border: none; background: transparent; font-size: 12px;">
                               </td>
                               <td style="padding: 8px; border: 1px solid #b0e0e6;">
                                   <input type="text" class="form-control" name="history_paper2[]" value="${paper2}" readonly style="width: 100%; padding: 8px; border: none; background: transparent; font-size: 12px;">
                               </td>
                               ${paper3Fields}
                               <td style="padding: 8px; border: 1px solid #b0e0e6; text-align: center;">
                                   <button type="button" class="btn btn-primary edit-history" style="background: #57afbfd6; border: none; padding: 8px 15px; font-size: 12px; color: white; border-radius: 4px; transition: background 0.3s;" onmouseover="this.style.background='#4682b4'" onmouseout="this.style.background='#57afbfd6'">
                                       <i class="fas fa-edit me-2"></i>Edit
                                   </button>
                               </td>
                           </tr>
                       </tbody>
                   </table>
               </div>
           `;

           if (appendSection) {
               appendSection.outerHTML = historyDetailsHtml;
           } else {
               const container = row.closest('.row-container') || row.closest('table').nextElementSibling;
               container.insertAdjacentHTML('beforeend', historyDetailsHtml);
           }

           $(`#${modalId}`).modal('hide');
       });

       // Handle edit button click
       $(document).off('click', '.edit-history').on('click', '.edit-history', function() {
           const historyDetails = $(this).closest('.history-details');
           const inputs = historyDetails.find('input');
           const routeInput = inputs.eq(0).val();
           const route = routeInput.split(': ')[0];
           const paper1 = inputs.eq(1).val();
           const paper2 = inputs.eq(2).val();
           const paper3_1 = inputs.eq(3).val();
           const paper3_2 = inputs.length > 4 ? inputs.eq(4).val() : '';
           const paper3 = paper3_2 ? `${paper3_1}<br>${paper3_2}` : paper3_1;

           showHistoryModal(row, { route, paper1, paper2, paper3 });
       });

       // Clean up modal on close
       $(`#${modalId}`).on('hidden.bs.modal', function() {
           $(this).remove();
       });
   }

   document.addEventListener('change', function(e) {
       if (e.target.classList.contains('exam-board')) {
           updateCourseTypes(e.target);
       } else if (e.target.classList.contains('course-type')) {
           updateSubjects(e.target);
       } else if (e.target.classList.contains('subject-name')) {
           checkHistorySelection(e.target);
       }
   });
</script>
