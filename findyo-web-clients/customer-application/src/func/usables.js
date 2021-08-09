import moment from 'moment'

export function findyoName (str) {
  return str ? str.toLowerCase().split(' ').join('_') : ''
}
export function languageLabel (langId, obj) {
  if (langId === 0) {
    // English
    return obj.name_en
  } else if (langId === 1) {
    // Sinhalese
    return obj.name_sn
  }
}
export function fromNow (time) {
  let nowLocal = moment.utc().format('YYYY-MM-DD HH:mm:ss')
  nowLocal = moment.utc(nowLocal).toDate()
  nowLocal = moment(nowLocal).local().format('YYYY-MM-DD HH:mm:ss')

  let postedDate = time.toDate()
  postedDate = moment.utc(postedDate).toDate()
  postedDate = moment(postedDate).local().format('YYYY-MM-DD HH:mm:ss')

  return moment(postedDate).from(moment(nowLocal))
}

export const completion = [
  {
    page_name: 'Basic Information',
    fields: [
      {
        type: 'single-text',
        label: 'Username',
        value: 'g.sandamal'
      },
      {
        type: 'single-text',
        label: 'First Name',
        value: 'gayan'
      },
      {
        type: 'single-text',
        label: 'Last Name',
        value: 'sandamal'
      },
      {
        type: 'single-text',
        label: 'Date of Birth',
        value: '1944-05-03'
      },
      {
        type: 'single-text',
        label: 'Job Title',
        value: 'UI Engineer'
      },
      {
        type: 'numeric-array',
        label: 'Contact Numbers',
        value: [
          {
            type: 'tel',
            value: '+94774101255'
          }
        ]
      }
    ]
  },
  {
    page_name: 'Location details',
    fields: [
      {
        type: 'single-text',
        label: 'Address',
        value: '220/A, Mackwatte, Asgiriya, Gampaha'
      },
      {
        type: 'input-array',
        label: 'Area',
        value: [
          {
            type: 'single-text',
            label: 'Country',
            value: ''
          },
          {
            type: 'single-text',
            label: 'province',
            value: ''
          },
          {
            type: 'single-text',
            label: 'District',
            value: ''
          },
          {
            type: 'single-text',
            label: 'City',
            value: ''
          },
          {
            type: 'single-numeric',
            label: 'Postal code',
            value: ''
          }
        ]
      }
    ]
  },
  {
    page_name: 'Profile description',
    fields: [
      {
        type: 'single-textarea',
        label: 'About you',
        value: 'Someone from nowhere'
      },
      {
        type: 'text-array',
        label: 'Skills',
        value: ['JAVA', 'Python', 'PHP', 'Lythium'],
        tag: ''
      },
      {
        type: 'text-array',
        label: 'Preferences and Interests',
        value: ['Soccer', 'Music'],
        tag: ''
      }
    ]
  }
]
