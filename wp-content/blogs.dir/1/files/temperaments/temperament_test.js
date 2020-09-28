
/*************************************************************************
**
** Temperament constants
**
**************************************************************************/

/**
 * Temperatment type identifiers
 */
var BEGIN_TEMPERAMENT = 100;

var SANGUINE = BEGIN_TEMPERAMENT + 1;
var CHOLERIC = BEGIN_TEMPERAMENT + 2;
var MEL      = BEGIN_TEMPERAMENT + 3;
var PHLEG    = BEGIN_TEMPERAMENT + 4;

var END_TEMPERAMENT = BEGIN_TEMPERAMENT + 5;

var CHOICES = 4;

/*************************************************************************
**
** HTML constants
**
**************************************************************************/

var QUOTE = "\"";
var LINE = "<HR size=\"1\"color=\"#000080\">";
var FONT = "Arial";

/*************************************************************************
**
** Variables
**
**************************************************************************/

var strength_count_sanguine = 0;
var strength_count_choleric = 0;
var strength_count_mel      = 0;
var strength_count_phleg    = 0;

var weak_count_sanguine = 0;
var weak_count_choleric = 0;
var weak_count_mel      = 0;
var weak_count_phleg    = 0;

var answer_rpt = "";
var unanswered_rpt = "";

/*************************************************************************
**
** Functions
**
**************************************************************************/

/**
 * Reset the UI and data
 * @param
 * @return
 * @since
 */
function ResetUi()
{
      if (!confirm( "Are you sure you want to reset? This will clear to whole test."))
       {
          return false;
       }
      Reset();
      document.totals.Reset.click();
      return true;
}

/**
 * Reset the data for this form...

 * @param
 * @return
 * @since
 */
function Reset()
{
   strength_count_sanguine = 0;
   strength_count_choleric = 0;
   strength_count_mel      = 0;
   strength_count_phleg    = 0;

   weak_count_sanguine = 0;
   weak_count_choleric = 0;
   weak_count_mel      = 0;
   weak_count_phleg    = 0;

   answer_rpt = "";
   unanswered_rpt = "";

   try
      {
      document.graph.ResetGraph(true);
      }
   catch(e)
      {
      }
}

/**
 * Processes the value from a given question...
 * @param
 * @return
 * @since
 */
function ProcessAnswer(question_num, is_strenth, answer)
{
   ++question_num; // convert from 0 based

   //------------------------------------------------------------------------------
   //: NOTE: "Opera" browers requires that braces be used in conditions!
   //------------------------------------------------------------------------------


  if (answer == SANGUINE)
     {
     if (is_strenth)
         {
         ++strength_count_sanguine;
         }
     else
         {
        ++weak_count_sanguine;
         }
     answer_rpt += question_num + ". Sanguine\n";
     }
  else if (answer == MEL)
     {
     if (is_strenth)
         {
         ++strength_count_mel;
         }
     else
         {
         ++weak_count_mel;
         }
     answer_rpt += question_num + ". Melancholy\n";
     }
  else if (answer == CHOLERIC)
     {
     if (is_strenth)
         {
         ++strength_count_choleric;
         }

     else
         {
        ++weak_count_choleric;
         }
     answer_rpt += question_num + ". Choleric\n";
     }
  else if (answer == PHLEG)
     {
     if (is_strenth)
         {
         ++strength_count_phleg;
         }
     else
         {
         ++weak_count_phleg;
         }
     answer_rpt += question_num + ". Phlegmatic\n";
     }
}


/**
 * Calculate score
 *
 * @param
 * @return
 * @since
 */
function CalcScore()
{
   Reset();

   dynamic_code = "";

   for (ii = 0; ii < questions.length ; ++ii)
      {
      dynamic_code += "answered  = false;"
      for (jj = 0; jj < CHOICES ; ++jj)
         {
            dynamic_code +=
            "if (document.totals.question" + ii + "[" + jj + "].checked) " +
                  "{" +
                  "ProcessAnswer(" + ii + "," + ii + "< 20," + "document.totals.question" + ii + "[" + jj + "].value);" +
                  "answered = true;" +
                  "}";
         }
       dynamic_code += "if (!answered)" +
                       "{" +
                       "unanswered_rpt += \" \" + " + (ii + 1) + " + \", \";" +
                       "}";

      }

   eval( dynamic_code );

   document.totals.strength_sanguine.value = strength_count_sanguine;
   document.totals.strength_choleric.value = strength_count_choleric;
   document.totals.strength_mel.value      = strength_count_mel;
   document.totals.strength_phleg.value    = strength_count_phleg;

   document.totals.weak_sanguine.value = weak_count_sanguine;
   document.totals.weak_choleric.value = weak_count_choleric;
   document.totals.weak_mel.value      = weak_count_mel;
   document.totals.weak_phleg.value    = weak_count_phleg;

   document.totals.total_sanguine.value = strength_count_sanguine + weak_count_sanguine;
   document.totals.total_choleric.value = strength_count_choleric + weak_count_choleric;
   document.totals.total_mel.value      = strength_count_mel + weak_count_mel;
   document.totals.total_phleg.value    = strength_count_phleg + weak_count_phleg;

   document.totals.rpt.value = answer_rpt;

   // DrawGraph(int mel, int choleric, int phleg, int sanguine) {
   try
      {
      document.graph.DrawGraph(strength_count_mel + weak_count_mel,
                              strength_count_choleric + weak_count_choleric,
                               strength_count_phleg + weak_count_phleg,
                               strength_count_sanguine + weak_count_sanguine);
      }
   catch (e)
      {
      }

   if (unanswered_rpt.length > 1)
      {
      unanswered_rpt = unanswered_rpt.substring( 0, unanswered_rpt.length - 2); // trim off last comma
      alert( "Please make sure you answered question(s): " + unanswered_rpt + "." );
      }
}

/**
 * Check to see if the given id is a valid temperament
 *
 * @param int id
 * @return boolean true if it's a temperament
 */
function isValidTemperament(id)
{
   return !(id <= BEGIN_TEMPERAMENT || id >= END_TEMPERAMENT);
}

/*************************************************************************
**
** Classes
**
**************************************************************************/

/**
 * CLASS: Question
 *
 * Defines a basic temperamenet question
 *
 * @param
 * @return
 */
function Question(choice_1_type, choice_1_text,
                  choice_2_type, choice_2_text,
                  choice_3_type, choice_3_text,
                  choice_4_type, choice_4_text)
{
   //------------------------------------------------------------------------------
   //:  Verify that valid temperatment types were given
   //------------------------------------------------------------------------------

   if (!isValidTemperament( choice_1_type ) || !isValidTemperament( choice_2_type ) ||
       !isValidTemperament( choice_3_type ) || !isValidTemperament( choice_4_type ))
      {
      throw new Exception( "invalid temperament type");
      }

   this.choice_1_type = choice_1_type
   this.choice_2_type = choice_2_type
   this.choice_3_type = choice_3_type
   this.choice_4_type = choice_4_type

   this.choice_1_text = choice_1_text
   this.choice_2_text = choice_2_text
   this.choice_3_text = choice_3_text
   this.choice_4_text = choice_4_text
}

/**
 * Exceptoin class
 * @param
 * @return
 * @since
 */
function Exception( error_description )
{
   this.error_description = error_description
   document.write( "<B>An error has occured: </B>" + error_description);
}

/*************************************************************************
**
** Main Question Array
**
**************************************************************************/


questions = new Array
   (
         //------------------------------------------------------------------------------
         //: Strengths
         //------------------------------------------------------------------------------

        new Question( SANGUINE, "Animated - Full of life, lively use of hand, arm, and face gestures.",
                      CHOLERIC, "Adventurous - One who will take on new and daring enterprises with a need to master them. ",
                      MEL,      "Analytical - One who is constantly in the process of analyzing people, places or things.",
                      PHLEG,    "Adaptable - One who easily adapts to any situation."),

        new Question( MEL,      "Persistent - Refusing to let go, insistently repetitive or continuous, can't drop it. ",
                      SANGUINE, "Playful - Full of fun and good humor.   ",
                      CHOLERIC, "Persuasive - One who persuades through logic and fact rather than charm. ",
                      PHLEG,    "Peaceful - One who seems undisturbed and tranquil and who retreats from any form of strife."),

        new Question( PHLEG,    "Submissive - One who easily submits to any other’s point of view or desire. This person has little need to assert his own view or opinion.  ",
                      MEL,      "Self-sacrificing - One who constantly sacrifices his/her own personal well being or for the sake or to meet the needs of others.  ",
                      SANGUINE, "Sociable - This sociable refers to one who sees being with others as an opportunity to be cute and entertaining.  If you are one who enjoys social gatherings as a challenge or business opportunity then do not check this word. ",
                      CHOLERIC, "Strong-willed - One who is determined to have his/her own way."),

        new Question( MEL,      "Considerate - Having regard for the needs and feelings of others. ",
                      PHLEG,    "Controlled - One who has emotional feelings but doesn't display them. ",
                      CHOLERIC, "Competitive - One who turns every situation, happening or game into to an arena for competition.  This person always plays to win. ",
                      SANGUINE, "Convincing - This person can convince you of anything through the sheer charm of his/her personality.  Facts are unimportant."),

        new Question( SANGUINE, "Refreshing - One who renews and stimulates or pleasantly lifts spirits. ",
                      MEL,      "Respectful - One who treats others with deference, honor, esteem.",
                      PHLEG,    "Reserved - Self-restraint in expression of emotion or enthusiasm. ",
                      CHOLERIC, "Resourceful - One who is able to act quickly and effectively in virtually all situations."),

        new Question( PHLEG,    "Satisfied - A person who easily accepts any circumstance or situation.  ",
                      MEL,      "Sensitive - This person is intensively sensitive to self and others. ",
                      CHOLERIC, "Self-reliant - An independent person who can fully rely on his own capabilities, judgment, and resources. ",
                      SANGUINE, "Spirited - One who is full of life and excitement."),

        new Question( MEL,      "Planner - One who prefers to work out a detailed arrangement beforehand, for the accomplishment of a project or goal.  This  person much prefers involvement with the planning stages and the finished product rather than the carrying out of the task.  ",
                      PHLEG,    "Patient - One who is unmoved by delay - calm and tolerant. ",
                      CHOLERIC, "Positive - Characterized by certainty and assurance.",
                      SANGUINE, "Promoter - One who can compel others to go along, join, or invest through the sheer charm of his/her own. "),

        new Question( CHOLERIC, "Sure - One who is confident, not hesitating or wavering. ",
                      SANGUINE, "Spontaneous - One who prefers all of life to be impulsive, unpremeditated activity.  This  person feels restricted by plans. ",
                      MEL,      "Scheduled - This person is controlled by his/her schedule and gets very upset if that schedule is interrupted.  There is another type of person who uses a schedule to stay organized, but is not controlled by the schedule.  If the second description is you, do not check this word.",
                      PHLEG,    "Shy - Quiet, doesn't easily instigate a conversation."),

        new Question( MEL,      "Orderly - A person who has a methodical, systematic arrangement of things.  Can be obsessively tidy. ",
                      PHLEG,    "Obliging - Accommodating.   One who is quick to do it another's way. ",
                      CHOLERIC, "Outspoken - One who speaks frankly and without reserve. ",
                      SANGUINE, "Optimistic - This optimist is an almost childlike, dreamer type of optimist."),

        new Question( PHLEG,    "Friendly - This person is a responder to friendliness rather than an initiator.  While he/she seldom starts a conversation he/she responds with great warmth and enjoys the exchange.",
                      MEL,      "Faithful - Consistently reliable.  Steadfast, loyal, and devoted sometimes beyond reason. ",
                      SANGUINE, "Funny - This person has an innate humor that can make virtually any story a funny one and is a remarkable joke teller.  If you have a dry humor, do not check this word. ",
                      CHOLERIC, "Forceful - A commanding personality.  One would hesitate to take a stand against this person."),

        new Question( CHOLERIC, "Daring - One who is willing to take risks; fearless, bold. ",
                      SANGUINE, "Delightful - A person who is greatly pleasing, fun to be with. ",
                      PHLEG,    "Diplomatic - One who deals with people both tactfully and sensitively. ",
                      MEL,      "Detailed - A person who prefers working with the minute or fields that require detail work such as math, research, accounting, carving, art, graphics, etc."),

        new Question( SANGUINE, "Cheerful - Constantly being in good spirits and promoting cheer. ",
                      PHLEG,    "Consistent - A person who is agreeable, compatible, not contradictory. ",
                      MEL,      "Cultured - One whose interests involve both intellectual and artistic pursuits, such as theater, symphony, ballet, etc.  ",
                      CHOLERIC, "Confident - One who is self-assured and/or certain of success."),

        new Question( MEL     , "Idealistic - One who visualizes in an ideal or perfect form, and has a need to measure up that standard. ",
                      CHOLERIC, "Independent - One who is self-sufficient, self-supporting, self-confident, and seems to have little need of help. ",
                      PHLEG,    "Inoffensive - A person who never causes offense, pleasant, unobjectionable, harmless. ",
                      SANGUINE, "Inspiring - One who encourages others to work, join, or be involved.  There is another personality that is deeply inspirational and has a need to being life-changing inspiration.  If you are the latter, do not check this word."), // todo

        new Question( SANGUINE, "Demonstrative - One who openly expresses emotion, especially affection.  This person doesn't hesitate to touch others while speaking to them. ",
                      CHOLERIC, "Decisive - A person with quick decision-making ability. ",
                      PHLEG,    "Dry Humor - One who exhibits dry wit, usually one-liners which can be sarcastic in nature, but very humorous. ",
                      MEL,      "Deep - A person who is intense and often introspective with a distaste for surface conversation and pursuits."),

        new Question( PHLEG,    "Mediator - A person who consistently finds him/herself in the role of reconciling differences in order to avoid conflict.",
                      MEL,      "Musical - One who either participates in or has an intense appreciation for music.   This type of musical would not include those who find it fun to sing or play.  The latter would be a different personality that enjoys being entertainer rather than one who is deeply committed to music as an art form.",
                      CHOLERIC, "Mover - One who is so driven by a need to be productive, that he/she finds it difficult to sit still.",
                      SANGUINE, "Mixes easily - One who loves a party and can't wait to meet everyone in the room, never meets a stranger."),

        new Question( MEL,      "Thoughtful - A considerate person who remembers special occasions and is quick to make a kind gesture.",
                      CHOLERIC, "Tenacious - One who holds on firmly, stubbornly, and won't let go till the goals are accomplished.",
                      SANGUINE, "Talker - A person who is constantly talking, generally telling funny stories and entertaining everyone around him/her.  There is another compulsive talker who is a nervous talker and feels the need to fill the silence in order to make others comfortable.",
                      PHLEG,    "Tolerant - One who easily accepts the thoughts and ways of others without the need to disagree with or change them."),

        new Question( PHLEG,    "Listener - One who always seems willing to listen. ",
                      MEL,      "Loyal - Faithful to a person, ideal, or job.  This person is sometimes is loyal beyond reason and to his/her own detriment. ",
                      CHOLERIC, "Leader - A person who is a born leader.  This is not one who rises to the occasion because they can lead, but one who is driven to lead and finds it very difficult to believe anyone else can do the job. ",
                      SANGUINE, "Lively - Full of life, vigorous, energetic."),

        new Question( PHLEG,    "Contented - One who is easily satisfied with what he/she has. ",
                      CHOLERIC, "Chief - A person who commands leadership.",
                      MEL,      "Chartmaker - One who enjoys charts, graphs, or lists. ",
                      SANGUINE, "Cute - Bubbly-Beauty, cutie, precious, diminutive."),

        new Question( MEL,      "Perfectionist - One who desires perfection, but not necessarily in every area of life. ",
                      PHLEG,    "Permissive - This person is permissive with employees, friends, and children in order to avoid conflict. ",
                      CHOLERIC, "Productive - One who must constantly be working and/or producing.  This person finds it very difficult to rest. ",
                      SANGUINE, "Popular - One who is the life of the party and therefore is much desired as a party guest."),

        //------------------------------------------------------------------------------
        //: Weaknesses
        //------------------------------------------------------------------------------


        new Question( SANGUINE, "Bouncy - A bubbly, lively personality.",
                      CHOLERIC, "Bold - Fearless, daring, forward.",
                      MEL,      "Behaved - One who consistently desires to conduct him/herself within the realm of what is proper. ",
                      PHLEG,    "Balanced - Stable, middle of the road personality, without extremes."),

        new Question( SANGUINE, "Brassy - One who is showy, flashy, comes on strong. ",
                      CHOLERIC, "Bossy - Commanding, domineering, overbearing.  (Do not relate this to the raising of children.  All mothers seem bossy and domineering.) Think only of adult relationships.",
                      MEL,      "Bashful - One who shrinks from notice, resulting from self-consciousness. ",
                      PHLEG,    "Blank - A person who shows little facial expression or emotion. "),

        new Question( SANGUINE, "Undisciplined - A person whose lack of discipline permeates virtually every area of his/her life.",
                      CHOLERIC, "Unsympathetic - One who finds it difficult to relate to the problems or hurts of others.",
                      PHLEG,    "Unenthusiastic - A person who finds it hard to get excited or feel enthusiastic.",
                      MEL,      "Unforgiving - One who has difficulty forgiving a hurt or injustice done to them.  This individual may find it hard to  release a grudge."),

        new Question( PHLEG,    "Reluctant - One who is unwilling or struggles against getting involved.",
                      MEL,      "Resentful - This person easily feels resentment as a result or real or imaged offenses.",
                      CHOLERIC, "Resistant - One who strives, works against, or resists accepting any other way but his/her own.",
                      SANGUINE, "Repetitious - This person retells stories and incidents to entertain you without realizing he/she has already told the story several times before.  This is not a question so much of forgetfulness, as it is of constantly needing something to say."),

        new Question( MEL,      "Fussy - One who is insistent over petty matters or details, calling for great attention to trivial details.",
                      PHLEG,    "Fearful - One who often experiences feelings of fear, apprehension, or anxiousness.",
                      SANGUINE, "Forgetful - This person is forgetful because it isn't fun to remember.  His/her forgetfulness is tied to a lack of discipline.  There is another personality that is more like the absent-minded professor.  This person tends to be off in another world and only remembers what he/she chooses to remember.  If you are the latter, do not check this word. ",
                      CHOLERIC, "Frank - One who is straightforward, outspoken, and doesn't mind telling you exactly what he/she thinks."),

        new Question( CHOLERIC, "Impatient - A person who finds it difficult to endure irritation or wait patiently.",
                      MEL,      "Insecure - One who is apprehensive or lacks confidence.",
                      PHLEG,    "Indecisive - This person finds it difficult to make a decision at all.  There is another personality that labors long over each decision in order to make the perfect one.  If you are the latter, do not check this word. ",
                      SANGUINE, "Interrupts - This person interrupts because he/she is afraid of forgetting the wonderful thing they have to say if another is allowed to finish.   This person is more of a talker than a listener."),

        new Question( CHOLERIC, "Unpopular - A person whose internally and demand for perfection can push others away.",         // TODO
                      MEL,      "Uninvolved - One who has no desire to become involved in clubs, groups, or people activities.",
                      SANGUINE, "Unpredictable - This person may be ecstatic one moment and blue the next, willing to help and then disappear, promoting to come and then forgetting to show up. ",
                      PHLEG,    "Unaffectionate - One who finds it difficult to verbally or physically demonstrate affection openly."),

        new Question( CHOLERIC, "Headstrong - One who insist on having his/her own way. ",
                      SANGUINE, "Haphazard - One who has no consistent way of doing things. ",
                      MEL     , "Hard to Please - A person whose standards are set so high that it is difficult to ever please them. ",
                      PHLEG,    "Hesitant - This person is slow to get moving and hard to get involved."),

        new Question( PHLEG,    "Plain - The middle-of-the-road personality without highs or lows and showing little if any emotion. ",
                      MEL,      "Pessimistic - This person, while hoping for the best, generally sees the down side of a situation. ",
                      CHOLERIC, "Proud - One with great self-esteem who sees him/herself as always right and the best person for the job. ",
                      SANGUINE, "Permissive - This personality allows others (including children) to do as they please in order to keep from being disliked."),

        new Question( SANGUINE, "Angered easily - One who has a childlike flash-in-the-pan temper that expresses itself in a child's tantrum style.  It is over and forgotten almost instantly. ",
                      PHLEG,    "Aimless- A person who is not a goal-seeker and has little desire to be one. ",
                      CHOLERIC, "Argumentative -One who incites argues generally because he/she is determined to be right no matter what the situation may be. ",
                      MEL,      "Alienated - A person who feels estranged from others often because of insecurity or fear that others don't  really enjoy his/her company."),

        new Question( SANGUINE, "Naive - A simple childlike perspective, lacking sophistication or worldliness.  This is not to be confused with uniformed.  There is another personality that is so consumed with his/her own particular field of interest that he/she simply could not care less what is going on outside of that sphere.  If you are the latter, do not check this word. ",
                      MEL,      "Negative   - One whose attitude is seldom positive and is often able to see only the down or dark side of each situation. ",
                      CHOLERIC, "Nervy - Full of confidence, fortitude, and sheer guts. ",
                      PHLEG,    "Nonchalant - Easy-going, unconcerned, indifferent."),

        new Question( PHLEG,    "Worrier - One who consistently feels uncertain or troubled. ",
                      MEL,      "Withdrawn - A person who pulls back to him/herself and needs a great deal of alone or  isolation time. ",
                      CHOLERIC, "Workaholic - This is one of two workaholic personalities.  This particular one is an aggressive goal-setter who most be constantly productive and feels guilty when resting.  This workaholic is not driven by a need for perfection or competition but by a need for accomplishment and reward. ",
                      SANGUINE, "Wants Credit - One who is almost dysfunctional without the credit or approval of others.  As an entertainer this person feeds on the applause, laugher, and/or acceptance of an audience."),

        new Question( MEL,      "Too sensitive - One who is overly sensitive and introspective. ",
                      CHOLERIC, "Tactless - A person who can sometimes express him/herself find a somewhat offensive and inconsiderate way.",
                      PHLEG,    "Timid - One who shrinks from difficult situations. ",
                      SANGUINE, "Talkative - A compulsive talker who finds it difficult to listen.  Again, this is an entertaining talker and not a nervous talker."),

        new Question( PHLEG,    "Doubtful - A person who is full of doubts, uncertain.",
                      SANGUINE, "Disorganized - One whose lack of organizational ability touches virtually every area of life.",
                      CHOLERIC, "Domineering - One who compulsively takes control of situations and/or people. Do not consider the mothering role. All mothers are somewhat domineering.",
                      MEL,      "Depressed - A person who struggles with bouts of depression of a fairly consistent basis."),

        new Question( SANGUINE, "Inconsistent - Erratic, contradictory, illogical.",
                      MEL,      "Introvert - A person whose thoughts and interest are directed inward. One who lives within him/herself.",
                      CHOLERIC, "Intolerant - One who appears unable to withstand or accept another's attitudes, points of view or way of doing things.",
                      PHLEG,     "Indifferent - A person to whom most things don't mater one way or the other."),

        new Question( SANGUINE, "Messy - This person is messy because it isn't fun to discipline him/herself to clean. The mess is hardly noticed. There is another personality that gets messy when depressed, and yet another that is messy because it takes too much energy to do the cleaning. Be sure you are the first one mentioned if you check this word.",
                      MEL,      "Moody - One easily slips into moods. This person doesn't get very high emotionally, but does experience very low lows. ",
                      PHLEG,    "Mumbles - This person may mumble quietly under the breath when pushed. This is a passive display of anger.",
                      CHOLERIC, "Manipulative - One who influences or manages shrewdly or deviously for one's own advantage. One who will find a way to get his/her own way."),

        new Question( PHLEG,    "Slow - One who is slow-moving, easy-going.",
                      CHOLERIC, "Stubborn - A person who is determined to exert his/her own will. Not easily persuaded; obstinate. ",
                      SANGUINE, "Show-off - One who needs to be the center of attention.",
                      MEL,      "Skeptical - Disbelieving, questioning the motive behind the words."),

        new Question( MEL,      "Loner - One who requires a lot of alone time and tends to avoid other people.",
                      CHOLERIC, "Lord over others - A person who doesn't hesitate to let you know that he/she is right or has won.",
                      PHLEG,    "Lazy - One who evaluates work or activity in terms of how much energy it will take. ",
                      SANGUINE, "Loud - A person whose laugh or voice can be heard above others in the room."),

        new Question( PHLEG,    "Sluggish - Slow to get started.",
                      MEL,      "Suspicious - One who tends to suspect or distrust.",
                      CHOLERIC, "Short-tempered - One who has a demanding impatience-based anger and a very short fuse. This type of anger is expressed when others are not moving fast enough or have not completed what they have been asked to do.",
                      SANGUINE, "Scatterbrained - A person lacking the power of concentration, or attention. Flightily."),

        new Question( MEL,      "Revengeful - One who knowingly or otherwise holds a grudge and punishes the offender, often by subtly withholding friendship or affection.",
                      SANGUINE, "Restless - A person who likes constant new activity because it isn't fun to do the same things all the time. ",
                      PHLEG,    "Reluctant - One who is unwilling or struggles against getting involved.",
                      CHOLERIC, "Rash - One who may act hastily, without thinking things through, generally because of impatience."),

        new Question( PHLEG,    "Compromising - A person who will often compromise, even when he/she is right, in order to avoid conflict.",
                      MEL,      "Critical - One who constantly evaluates and makes judgment. Example: One who is critical might see someone coming down the street and within seconds might try to evaluate their cleanliness, look of intelligence or lack of it, style of clothing or lack of it, physical attractiveness or lack of it, and the list goes on. This person constantly analyzes and critiques, sometimes without realizing that he/she is doing so.",
                      CHOLERIC, "Crafty - Shrewd, one who can always find a way to get to the desired end. ",
                      SANGUINE, "Changeable - A person with a childlike short attention span that needs a lot of change and variety to keep from getting bored.")

/*
        new Question( MEL,      "",
                      PHLEG,    "",
                      CHOLERIC, "",
                      SANGUINE, "")
*/


   );

/**
 * Format the text of a question
 * @param
 * @return
 * @since
 */
function FormatText(text)
{
   //------------------------------------------------------------------------------
   //: Break it into 2 strings, then format it and put is back together
   //------------------------------------------------------------------------------

   index = text.indexOf( " - " );

   first_part = text.substring( 0, index );
   second_part = text.substring( index, text.length);

   formatted = "<font size=\"2\" color=\"#000080\" face=\"" + FONT + ", Helvetica, sans-serif\"><B>" + first_part + "</B></font>" +
                 "<font size=\"2\" face=\"" + FONT + ", Helvetica, sans-serif\">" + second_part + "</font>";
   return formatted;

}

/**
 * Generates a radio button with the given value & name, & text
 * @param String value
 * @param String name
 * @param String text
 *
 * @return HTML for a radio button
 */
function RadioButton(value, name, text)
{
   html = "<TR> <TD width =\"25\" valign=\"top\">" + "<input type=\"radio\" value=\"" + value  +QUOTE + " name=\"question" + name + QUOTE + ">" +
          "</TD> <TD>" + FormatText(text);
   return html;
}

/**
 * Builds QUestion number column
 * @param
 * @return
 * @since
 */
function BuildQuestionNum(question_num)
{
   var html2 =  "<TR><td rowspan=\"5\" width=\"25\" valign=\"top\"><font size=\"3\"  color=\"#000080\" face=\"" +
                FONT + ", Helvetica, sans-serif\"> <B>" +
                (question_num + 1) + ". " + "</td></TR></font></B>";
   return html2;
}

/**
 * Format the given string as a header
 * @param
 * @return
 * @since
 */
function FormatHeader(text)
{
   var html2 =  "<BR><div align=\"center\"><font size=\"6\"  color=\"#000080\" face=\"" +
                FONT + ", Helvetica, sans-serif\"> <B>" +
               text + " </font></B></div><BR>";
   return html2;
}



/*************************************************************************
**
** Free Code -- builds the screen
**
**************************************************************************/


document.write( "<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">");

    for (ii = 0; ii < questions.length ; ++ii)
       {
       if (ii == 0)
          {
          //document.write( BuildRow() );
          document.write( FormatHeader( "Strengths"));
          }
       else if (ii == 20)
          {
          document.write( FormatHeader("Weaknesses"));
          }

       document.write(BuildQuestionNum( ii ));
       document.write( RadioButton(questions[ii].choice_1_type, ii, questions[ii].choice_1_text));
       document.write( RadioButton(questions[ii].choice_2_type, ii, questions[ii].choice_2_text));
       document.write( RadioButton(questions[ii].choice_3_type, ii, questions[ii].choice_3_text));
       document.write( RadioButton(questions[ii].choice_4_type, ii, questions[ii].choice_4_text));

       document.write( LINE );
       }

document.write( "</table>");

